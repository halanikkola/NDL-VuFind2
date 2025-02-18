<?php
$config = [
    'extends' => 'bootstrap3',
    'helpers' => [
        'factories' => [
            'Finna\View\Helper\Root\Auth' => 'Finna\View\Helper\Root\AuthFactory',
            'Finna\View\Helper\Root\AuthorizationNotification' => 'Finna\View\Helper\Root\AuthorizationNotificationFactory',
            'Finna\View\Helper\Root\Authority' => 'Finna\View\Helper\Root\AuthorityFactory',
            'Finna\View\Helper\Root\Autocomplete' => 'Finna\View\Helper\Root\AutocompleteFactory',
            'Finna\View\Helper\Root\Barcode' => 'Laminas\ServiceManager\Factory\InvokableFactory',
            'Finna\View\Helper\Root\Browse' => 'Finna\View\Helper\Root\BrowseFactory',
            'Finna\View\Helper\Root\Callnumber' => 'Finna\View\Helper\Root\CallNumberFactory',
            'Finna\View\Helper\Root\Citation' => 'Finna\View\Helper\Root\CitationFactory',
            'Finna\View\Helper\Root\CleanHtml' => 'Finna\View\Helper\Root\CleanHtmlFactory',
            'Finna\View\Helper\Root\Combined' => 'Finna\View\Helper\Root\CombinedFactory',
            'Finna\View\Helper\Root\Config' => 'VuFind\View\Helper\Root\ConfigFactory',
            'Finna\View\Helper\Root\Content' => 'Laminas\ServiceManager\Factory\InvokableFactory',
            'Finna\View\Helper\Root\Cookie' => 'Finna\View\Helper\Root\CookieFactory',
            'Finna\View\Helper\Root\CustomElement' => 'Finna\View\Helper\Root\CustomElementFactory',
            'Finna\View\Helper\Root\EDS' => 'Finna\View\Helper\Root\EDSFactory',
            'Finna\View\Helper\Root\Feed' => 'Finna\View\Helper\Root\FeedFactory',
            'Finna\View\Helper\Root\FeedTabs' => 'Laminas\ServiceManager\Factory\InvokableFactory',
            'Finna\View\Helper\Root\FileSize' => 'Laminas\ServiceManager\Factory\InvokableFactory',
            'Finna\View\Helper\Root\FileSrc' => 'Finna\View\Helper\Root\HelperWithThemeInfoFactory',
            'Finna\View\Helper\Root\FinnaSurvey' => 'Finna\View\Helper\Root\HelperWithMainConfigFactory',
            'Finna\View\Helper\Root\Followup' => 'Finna\View\Helper\Root\FollowupFactory',
            'Finna\View\Helper\Root\HtmlElement' => 'Laminas\ServiceManager\Factory\InvokableFactory',
            'Finna\View\Helper\Root\Holdings' => 'Finna\View\Helper\Root\HelperWithMainConfigFactory',
            'Finna\View\Helper\Root\ImageSrc' => 'Finna\View\Helper\Root\HelperWithThemeInfoFactory',
            'Finna\View\Helper\Root\LayoutClass' => 'VuFind\View\Helper\Bootstrap3\LayoutClassFactory',
            'Finna\View\Helper\Root\LinkedEventsTabs' => 'Laminas\ServiceManager\Factory\InvokableFactory',
            'Finna\View\Helper\Root\Markdown' => 'VuFind\View\Helper\Root\MarkdownFactory',
            'Finna\View\Helper\Root\Matomo' => 'Finna\View\Helper\Root\MatomoFactory',
            'Finna\View\Helper\Root\MetaLib' => 'Laminas\ServiceManager\Factory\InvokableFactory',
            'Finna\View\Helper\Root\Navibar' => 'Finna\View\Helper\Root\NavibarFactory',
            'Finna\View\Helper\Root\R2' => 'Finna\View\Helper\Root\R2Factory',
            'Finna\View\Helper\Root\OnlinePayment' => 'Laminas\ServiceManager\Factory\InvokableFactory',
            'Finna\View\Helper\Root\OpenUrl' => 'Finna\View\Helper\Root\OpenUrlFactory',
            'Finna\View\Helper\Root\OrganisationDisplayName' => 'Laminas\ServiceManager\Factory\InvokableFactory',
            'Finna\View\Helper\Root\OrganisationInfo' => 'Finna\View\Helper\Root\OrganisationInfoFactory',
            'Finna\View\Helper\Root\OrganisationsList' => 'Finna\View\Helper\Root\OrganisationsListFactory',
            'Finna\View\Helper\Root\PersonaAuth' => 'Laminas\ServiceManager\Factory\InvokableFactory',
            'Finna\View\Helper\Root\Piwik' => 'VuFind\View\Helper\Root\PiwikFactory',
            'Finna\View\Helper\Root\Primo' => 'Finna\View\Helper\Root\PrimoFactory',
            'Finna\View\Helper\Root\ProxyUrl' => 'Finna\View\Helper\Root\ProxyUrlFactory',
            'Finna\View\Helper\Root\Recaptcha' => 'Laminas\ServiceManager\Factory\InvokableFactory',
            'Finna\View\Helper\Root\Record' => 'Finna\View\Helper\Root\RecordFactory',
            'Finna\View\Helper\Root\RecordDataFormatter' => 'Finna\View\Helper\Root\RecordDataFormatterFactory',
            'Finna\View\Helper\Root\RecordFieldMarkdown' => 'Finna\View\Helper\Root\RecordFieldMarkdownFactory',
            'Finna\View\Helper\Root\RecordImage' => 'Laminas\ServiceManager\Factory\InvokableFactory',
            'Finna\View\Helper\Root\RecordLink' => 'Finna\View\Helper\Root\RecordLinkFactory',
            'Finna\View\Helper\Root\RecordLinker' => 'Finna\View\Helper\Root\RecordLinkerFactory',
            'Finna\View\Helper\Root\ResultFeed' => 'VuFind\View\Helper\Root\ResultFeedFactory',
            'Finna\View\Helper\Root\ScriptSrc' => 'Finna\View\Helper\Root\HelperWithThemeInfoFactory',
            'Finna\View\Helper\Root\Search' => 'Laminas\ServiceManager\Factory\InvokableFactory',
            'Finna\View\Helper\Root\SearchBox' => 'VuFind\View\Helper\Root\SearchBoxFactory',
            'Finna\View\Helper\Root\SearchMemory' => 'VuFind\View\Helper\Root\SearchMemoryFactory',
            'Finna\View\Helper\Root\SearchTabs' => 'Finna\View\Helper\Root\SearchTabsFactory',
            'Finna\View\Helper\Root\SearchTabsRecommendations' => 'Finna\View\Helper\Root\SearchTabsRecommendationsFactory',
            'Finna\View\Helper\Root\StreetSearch' => 'Laminas\ServiceManager\Factory\InvokableFactory',
            'Finna\View\Helper\Root\StripTags' => 'Laminas\ServiceManager\Factory\InvokableFactory',
            'Finna\View\Helper\Root\Summon' => 'Finna\View\Helper\Root\SummonFactory',
            'Finna\View\Helper\Root\SystemMessages' => 'Finna\View\Helper\Root\SystemMessagesFactory',
            'Finna\View\Helper\Root\TotalIndexed' => 'Finna\View\Helper\Root\TotalIndexedFactory',
            'Finna\View\Helper\Root\Translation' => 'Finna\View\Helper\Root\TranslationFactory',
            'Finna\View\Helper\Root\TranslationEmpty' => 'Laminas\ServiceManager\Factory\InvokableFactory',
            'Finna\View\Helper\Root\TruncateUrl' => 'Laminas\ServiceManager\Factory\InvokableFactory',
            'Finna\View\Helper\Root\UserAgent' => 'Finna\View\Helper\Root\UserAgentFactory',
            'Finna\View\Helper\Root\UserListEmbed' => 'Finna\View\Helper\Root\UserListEmbedFactory',
            'Finna\View\Helper\Root\UserPublicName' => 'Laminas\ServiceManager\Factory\InvokableFactory',

            'VuFind\View\Helper\Root\Linkify' => 'Finna\View\Helper\Root\LinkifyFactory',
        ],
        'aliases' => [
            'auth' => 'Finna\View\Helper\Root\Auth',
            'authority' => 'Finna\View\Helper\Root\Authority',
            'authorizationNote' => 'Finna\View\Helper\Root\AuthorizationNotification',
            'autocomplete' => 'Finna\View\Helper\Root\Autocomplete',
            'barcode' => 'Finna\View\Helper\Root\Barcode',
            'callnumber' => 'Finna\View\Helper\Root\Callnumber',
            'cleanHtml' => 'Finna\View\Helper\Root\CleanHtml',
            'combined' => 'Finna\View\Helper\Root\Combined',
            'content' => 'Finna\View\Helper\Root\Content',
            'cookie' => 'Finna\View\Helper\Root\Cookie',
            'customElement' => 'Finna\View\Helper\Root\CustomElement',
            'eds' => 'Finna\View\Helper\Root\EDS',
            'feed' => 'Finna\View\Helper\Root\Feed',
            'feedTabs' => 'Finna\View\Helper\Root\FeedTabs',
            'fileSize' => 'Finna\View\Helper\Root\FileSize',
            'fileSrc' => 'Finna\View\Helper\Root\FileSrc',
            'finnaSurvey' => 'Finna\View\Helper\Root\FinnaSurvey',
            'followup' => 'Finna\View\Helper\Root\Followup',
            'holdings' => 'Finna\View\Helper\Root\Holdings',
            // For back-compatibility
            'holdingsSettings' => 'Finna\View\Helper\Root\Holdings',
            'htmlElement' => 'Finna\View\Helper\Root\HtmlElement',
            //use root highlight so search results use span instead of mark
            'highlight' => 'VuFind\View\Helper\Root\Highlight',
            'imageSrc' => 'Finna\View\Helper\Root\ImageSrc',
            'indexedTotal' => 'Finna\View\Helper\Root\TotalIndexed',
            'linkedEventsTabs' => 'Finna\View\Helper\Root\LinkedEventsTabs',
            'markdown' => 'Finna\View\Helper\Root\Markdown',
            'metaLib' => 'Finna\View\Helper\Root\MetaLib',
            'navibar' => 'Finna\View\Helper\Root\Navibar',
            'R2' => 'Finna\View\Helper\Root\R2',
            'onlinePayment' => 'Finna\View\Helper\Root\OnlinePayment',
            'organisationInfo' => 'Finna\View\Helper\Root\OrganisationInfo',
            'organisationDisplayName' => 'Finna\View\Helper\Root\OrganisationDisplayName',
            'organisationsList' => 'Finna\View\Helper\Root\OrganisationsList',
            'personaAuth' => 'Finna\View\Helper\Root\PersonaAuth',
            'primo' => 'Finna\View\Helper\Root\Primo',
            // recaptcha only exists for back-compatibility
            'recaptcha' => 'Finna\View\Helper\Root\Recaptcha',
            'recordFieldMarkdown' => 'Finna\View\Helper\Root\RecordFieldMarkdown',
            'recordImage' => 'Finna\View\Helper\Root\RecordImage',
            'scriptSrc' => 'Finna\View\Helper\Root\ScriptSrc',
            'stripTags' => 'Finna\View\Helper\Root\StripTags',
            'search' => 'Finna\View\Helper\Root\Search',
            'searchbox' => 'Finna\View\Helper\Root\SearchBox',
            'searchMemory' => 'Finna\View\Helper\Root\SearchMemory',
            'searchTabsRecommendations' => 'Finna\View\Helper\Root\SearchTabsRecommendations',
            'streetSearch' => 'Finna\View\Helper\Root\StreetSearch',
            'systemMessages' => 'Finna\View\Helper\Root\SystemMessages',
            'translation' => 'Finna\View\Helper\Root\Translation',
            'translationEmpty' => 'Finna\View\Helper\Root\TranslationEmpty',
            'truncateUrl' => 'Finna\View\Helper\Root\TruncateUrl',
            'userAgent' => 'Finna\View\Helper\Root\UserAgent',
            'userlistEmbed' => 'Finna\View\Helper\Root\UserListEmbed',
            'userPublicName' => 'Finna\View\Helper\Root\UserPublicName',

            // Overrides
            'VuFind\View\Helper\Root\Browse' => 'Finna\View\Helper\Root\Browse',
            'VuFind\View\Helper\Root\Citation' => 'Finna\View\Helper\Root\Citation',
            'VuFind\View\Helper\Root\Config' => 'Finna\View\Helper\Root\Config',
            'VuFind\View\Helper\Root\Matomo' => 'Finna\View\Helper\Root\Matomo',
            'VuFind\View\Helper\Root\OpenUrl' => 'Finna\View\Helper\Root\OpenUrl',
            'VuFind\View\Helper\Root\Piwik' => 'Finna\View\Helper\Root\Piwik',
            'VuFind\View\Helper\Root\ProxyUrl' => 'Finna\View\Helper\Root\ProxyUrl',
            'VuFind\View\Helper\Root\Record' => 'Finna\View\Helper\Root\Record',
            'VuFind\View\Helper\Root\RecordDataFormatter' => 'Finna\View\Helper\Root\RecordDataFormatter',
            'VuFind\View\Helper\Root\RecordLink' => 'Finna\View\Helper\Root\RecordLink',
            'VuFind\View\Helper\Root\RecordLinker' => 'Finna\View\Helper\Root\RecordLinker',
            'VuFind\View\Helper\Root\ResultFeed' => 'Finna\View\Helper\Root\ResultFeed',
            'VuFind\View\Helper\Root\SearchTabs' => 'Finna\View\Helper\Root\SearchTabs',
            'VuFind\View\Helper\Root\Summon' => 'Finna\View\Helper\Root\Summon',
            'VuFind\View\Helper\Bootstrap3\LayoutClass' => 'Finna\View\Helper\Root\LayoutClass',

            // Aliases for non-standard cases
            'Combined' => 'combined',
            'KeepAlive' => 'keepAlive',
            'MetaLib' => 'metaLib',
            'metalib' => 'metaLib',
            'Primo' => 'primo',
            'proxyurl' => 'proxyUrl',
            'searchtabs' => 'searchTabs',
            'transesc' => 'transEsc',
            'inlinescript' => 'inlineScript',
        ]
    ],
    'css' => [
        'vendor/bootstrap-datepicker3.min.css',
        'vendor/bootstrap-rating.min.css',
        'vendor/bootstrap-slider.min.css',
        'vendor/dataTables.bootstrap.min.css',
        'vendor/L.Control.Locate.min.css',
        'vendor/leaflet.css',
        'vendor/leaflet.draw.css',
        'vendor/easymde.min.css',
        'vendor/slick.css',
        'vendor/slick-theme.css',
        'vendor/video-js.min.css',
        'vendor/select2.min.css',
        'finna.css',
        'vendor/priority-nav-core.css',
        'finna-flex-fallback.css::lt IE 10', // flex polyfill
    ],
    'js' => [
        'vendor/event-stub.js:lt IE 9',
        'account_ajax.js',
        'advanced_search.js',
        'cart.js',
        'check_item_statuses.js',
        'check_save_statuses.js',
        'collection_record.js',
        'combined-search.js',
        'embedded_record.js',
        'facets.js',
        'keep_alive.js',
        'openurl.js',
        'record.js',
        'record_versions.js',
        'requests.js',
        'finna-polyfill.js',
        'finna.js',
        'finna-popup.js',
        'finna-autocomplete.js',
        'finna-authority.js',
        'finna-combined-results.js',
        'finna-model-viewer.js',
        'finna-video-popup.js',
        'finna-image-paginator.js',
        'finna-menu-movement.js',
        'finna-comments.js',
        'finna-common.js',
        'finna-content-feed.js',
        'finna-item-status.js',
        'finna-adv-search.js',
        'finna-daterange-vis.js',
        'finna-feed.js',
        'finna-layout.js',
        'finna-linked-events.js',
        'finna-openurl.js',
        'finna-map.js',
        'finna-map-facet.js',
        'finna-menu.js',
        'finna-mylist.js',
        'finna-online-payment.js',
        'finna-organisation-info.js',
        'finna-organisation-info-page.js',
        'finna-organisation-info-page-consortium.js',
        'finna-organisation-info-widget.js',
        'finna-organisation-map-leaflet.js',
        'finna-primo-adv-search.js',
        'finna-R2.js',
        'finna-recommendation-memory.js',
        'finna-record.js',
        'finna-search-tabs-recommendations.js',
        'finna-street-search.js',
        'vendor/bootstrap-datepicker.min.js',
        'vendor/bootstrap-datepicker.en-GB.min.js',
        'vendor/bootstrap-datepicker.fi.min.js',
        'vendor/bootstrap-datepicker.sv.min.js',
        'vendor/bootstrap-rating.min.js',
        'vendor/bootstrap-slider.min.js',
        'vendor/hunt.min.js',
        'vendor/jquery.colorhelpers.min.js',
        'vendor/jquery.dataTables.min.js',
        'vendor/dataTables.bootstrap.min.js',
        'vendor/jquery.editable.min.js',
        'vendor/jquery.flot.min.js',
        'vendor/jquery.flot.selection.min.js',
        'vendor/jquery.inview.min.js',
        'vendor/jquery.unveil.min.js',
        'vendor/jsTree/jstree.min.js',
        'vendor/sortable.min.js',
        'vendor/easymde.min.js',
        'vendor/slick.min.js',
        'vendor/gauge.min.js',
        'vendor/select2.min.js',
        'vendor/priority-nav.min.js',
        'vendor/leaflet.min.js',
        'vendor/leaflet.draw.min.js',
        'vendor/js.cookie.js',
        'finna-multiselect.js'
    ],
    'less' => [
        'active' => false
    ],
    'favicon' => 'favicon.ico',
];
include 'components.config.php';
return $config;
