<?php
/**
 * Model for EAC-CPF records in Solr.
 *
 * PHP version 5
 *
 * Copyright (C) Villanova University 2010.
 * Copyright (C) The National Library of Finland 2012-2019.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category VuFind
 * @package  RecordDrivers
 * @author   Ere Maijala <ere.maijala@helsinki.fi>
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/vufind2:record_drivers Wiki
 */
namespace Finna\RecordDriver;

/**
 * Model for EAC-CPF records in Solr.
 *
 * @category VuFind
 * @package  RecordDrivers
 * @author   Ere Maijala <ere.maijala@helsinki.fi>
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/vufind2:record_drivers Wiki
 */
class SolrAuthEacCpf extends SolrAuthDefault
{
    use Feature\SolrAuthFinnaTrait {
        getOccupations as _getOccupations;
    }
    use Feature\FinnaXmlReaderTrait;

    /**
     * Get authority title
     *
     * @return string
     */
    public function getTitle()
    {
        $firstTitle = '';
        $record = $this->getXmlRecord();
        if (isset($record->cpfDescription->identity->nameEntry)) {
            $languages = $this->mapLanguageCode($this->getLocale());
            $name = $record->cpfDescription->identity->nameEntry;
            if (!isset($name->part)) {
                return '';
            }
            $lang = (string)$name->attributes()->lang;
            foreach ($name->part as $part) {
                if ($title = (string)$part) {
                    if (!$firstTitle) {
                        $firstTitle = $title;
                    }
                    if ($lang && in_array($lang, $languages)) {
                        return $title;
                    }
                }
            }
        }
        return $firstTitle;
    }

    /**
     * Get an array of alternative titles for the record.
     *
     * @return array
     */
    public function getAlternativeTitles()
    {
        $titles = [];
        $path = 'cpfDescription/identity/nameEntryParallel/nameEntry';
        foreach ($this->getXmlRecord()->xpath($path) as $name) {
            foreach ($name->part ?? [] as $part) {
                $localType = (string)$part->attributes()->localType;
                if ($localType === 'http://rdaregistry.info/Elements/a/P50103') {
                    $titles[] = ['data' => (string)$part];
                }
            }
        }
        return $titles;
    }

    /**
     * Return description
     *
     * @return array|null
     */
    public function getSummary()
    {
        $record = $this->getXmlRecord();
        if (isset($record->cpfDescription->description->biogHist->p)) {
            return [(string)$record->cpfDescription->description->biogHist->p];
        }
        return null;
    }

    /**
     * Return birth date.
     *
     * @param boolean $force Return established date for corporations?
     *
     * @return string
     */
    public function getBirthDate($force = false)
    {
        if (!$this->isPerson() && !$force) {
            return '';
        }
        return $this->formatDate(
            $this->getExistDate('http://rdaregistry.info/Elements/a/P50120') ?? ''
        );
    }

    /**
     * Return death date.
     *
     * @param boolean $force Return terminated date for corporations?
     *
     * @return string
     */
    public function getDeathDate($force = false)
    {
        if (!$this->isPerson() && !$force) {
            return '';
        }
        return $this->formatDate(
            $this->getExistDate('http://rdaregistry.info/Elements/a/P50121') ?? ''
        );
    }

    /**
     * Return exist date
     *
     * @param string $localType localType attribute
     *
     * @return null|string
     */
    protected function getExistDate(string $localType) : ?string
    {
        $record = $this->getXmlRecord();
        if (!isset($record->cpfDescription->description->existDates->dateSet->date)
        ) {
            return null;
        }
        foreach ($record->cpfDescription->description->existDates->dateSet->date
            as $date
        ) {
            $attrs = $date->attributes();
            $type = (string)$attrs->localType;
            if ($localType === $type) {
                return (string)$attrs->standardDate;
            }
        }
        return null;
    }

    /**
     * Get related places
     *
     * @return array
     */
    public function getRelatedPlaces()
    {
        $record = $this->getXmlRecord();
        if (!isset($record->cpfDescription->description->places->place)) {
            return '';
        }
        $result = [];
        $languages = $this->mapLanguageCode($this->getLocale());
        foreach ($record->cpfDescription->description->places->place as $place) {
            $attr = $place->attributes();
            if ($attr->placeEntry
                && !$attr->lang || in_array((string)$attr->lang, $languages)
            ) {
                $result[] = [
                    'data' => (string)$place->placeEntry,
                    'detail' => (string)$place->placeRole
                ];
            }
        }
        return $result;
    }

    /**
     * Return relations to other authority records.
     *
     * @return array
     */
    public function getRelations()
    {
        $record = $this->getXmlRecord();
        $result = [];
        foreach ($record->cpfDescription->relations->cpfRelation ?? []
            as $relation
        ) {
            $attr = $relation->attributes();
            $id = (string)$attr->href;
            $name = (string)$relation->relationEntry;
            if ($id && $name) {
                $result[] = [
                    'id' => $id,
                    'name' => $name,
                    'role' => (string)$attr->title,
                ];
            }
        }

        return $result;
    }

    /**
     * Return occupations.
     *
     * @return array
     */
    public function getOccupations()
    {
        $result = [];
        $record = $this->getXmlRecord();
        if (isset($record->cpfDescription->description->occupations)) {
            $languages = $this->mapLanguageCode($this->getLocale());
            foreach ($record->cpfDescription->description->occupations
                as $occupations
            ) {
                if (!isset($occupations->occupation)) {
                    continue;
                }
                foreach ($occupations->occupation as $occupation) {
                    if (!isset($occupation->term)) {
                        continue;
                    }
                    $term = $occupation->term;
                    $attr = $term->attributes();
                    if ($attr->lang && in_array((string)$attr->lang, $languages)
                    ) {
                        $result[] = (string)$term;
                    }
                }
            }
        }
        return $result ?: $this->_getOccupations();
    }

    /**
     * Set preferred language for display strings.
     *
     * @param string $language Language
     *
     * @return void
     */
    public function setPreferredLanguage($language)
    {
    }

    /**
     * Format date
     *
     * @param string $date Date
     *
     * @return string
     */
    protected function formatDate($date)
    {
        if (!$this->dateConverter) {
            return $date;
        }
        try {
            if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $date)) {
                return $this->dateConverter->convertToDisplayDate('Y-m-d', $date);
            } elseif (preg_match('/^(\d{4})$/', $date)) {
                return $this->dateConverter->convertFromDisplayDate(
                    'Y',
                    $this->dateConverter->convertToDisplayDate('Y', $date)
                );
            }
        } catch (\Exception $e) {
        }
        return $this->translate(ucfirst($date), [], $date);
    }

    /**
     * Convert Finna language codes to EAD3 codes.
     *
     * @param string $languageCode Language code
     *
     * @return string[]
     */
    protected function mapLanguageCode($languageCode)
    {
        $langMap
            = ['fi' => ['fi','fin'], 'sv' => ['sv','swe'], 'en' => ['en','eng']];
        return $langMap[$languageCode] ?? [$languageCode];
    }
}
