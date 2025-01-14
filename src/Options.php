<?php

namespace WP2Static;

use WP_CLI;

class Options {
    public $wp2static_options = [];
    public $wp2static_option_key = null;
    public $wp2static_options_keys = [
        'additionalUrls',
        'allowOfflineUsage',
        'baseHREF',
        'baseUrl',
        'baseUrlfolder',
        'baseUrlzip',
        'basicAuthPassword',
        'basicAuthUser',
        'completionEmail',
        'crawlPort',
        'crawlUserAgent',
        'crawlDelay',
        'createEmptyFavicon',
        'currentDeploymentMethod',
        'currentDeploymentMethodProduction',
        'delayBetweenAPICalls',
        'deployBatchSize',
        'detectArchives',
        'detectAttachments',
        'detectCategoryPagination',
        'detectChildTheme',
        'detectCommentPagination',
        'detectComments',
        'detectCustomPostTypes',
        'detectFeedURLs',
        'detectHomepage',
        'detectPages',
        'detectParentTheme',
        'detectPluginAssets',
        'detectPostPagination',
        'detectPosts',
        'detectUploads',
        'detectVendorCacheDirs',
        'detectWPIncludesAssets',
        'displayDashboardWidget',
        'dontUseCrawlCaching',
        'excludeURLs',
        'forceHTTPS',
        'forceRewriteSiteURLs',
        'includeDiscoveredAssets',
        'parseCSS',
        'redeployOnPostUpdates',
        'removeCanonical',
        'removeConditionalHeadComments',
        'removeHTMLComments',
        'removeWPLinks',
        'removeRobotsNoIndex',
        'removeWPMeta',
        'renameRules',
        'rewriteRules',
        'targetFolder',
        'useActiveFTP',
        'useBaseHref',
        'useBasicAuth',
        'useDocumentRelativeURLs',
        'useSiteRootRelativeURLs',
    ];

    public $whitelisted_keys = [
        'additionalUrls',
        'allowOfflineUsage',
        'baseHREF',
        'baseUrl',
        'baseUrlfolder',
        'baseUrlzip',
        'basicAuthUser',
        'completionEmail',
        'crawlPort',
        'crawlUserAgent',
        'crawlDelay',
        'createEmptyFavicon',
        'currentDeploymentMethod',
        'currentDeploymentMethodProduction',
        'delayBetweenAPICalls',
        'deployBatchSize',
        'detectArchives',
        'detectAttachments',
        'detectCategoryPagination',
        'detectChildTheme',
        'detectCommentPagination',
        'detectComments',
        'detectCustomPostTypes',
        'detectFeedURLs',
        'detectHomepage',
        'detectPages',
        'detectParentTheme',
        'detectPluginAssets',
        'detectPostPagination',
        'detectPosts',
        'detectUploads',
        'detectVendorCacheDirs',
        'detectWPIncludesAssets',
        'displayDashboardWidget',
        'dontUseCrawlCaching',
        'excludeURLs',
        'forceHTTPS',
        'forceRewriteSiteURLs',
        'ghBranch',
        'ghCommitMessage',
        'ghPath',
        'ghRepo',
        'includeDiscoveredAssets',
        'parseCSS',
        'redeployOnPostUpdates',
        'removeCanonical',
        'removeConditionalHeadComments',
        'removeHTMLComments',
        'removeWPLinks',
        'removeRobotsNoIndex',
        'removeWPMeta',
        'renameRules',
        'rewriteRules',
        'targetFolder',
        'useActiveFTP',
        'useBaseHref',
        'useBasicAuth',
        'useDocumentRelativeURLs',
        'useSiteRootRelativeURLs',
    ];

    public function __construct( string $option_key ) {
        $this->wp2static_options_keys = apply_filters(
            'wp2static_add_option_keys',
            $this->wp2static_options_keys
        );

        $this->whitelisted_keys = apply_filters(
            'wp2static_whitelist_option_keys',
            $this->whitelisted_keys
        );

        $options = get_option( $option_key );

        if ( false === $options ) {
            $options = array();
        }

        $this->wp2static_options = $options;
        $this->wp2static_option_key = $option_key;
    }

    /**
     *  Set an option
     *
     * @param mixed $value new value for option
     * @throws WP2StaticException
     */
    public function __set( string $name, $value ) : Options {
        $this->wp2static_options[ $name ] = $value;

        if ( ! array_key_exists( $name, $this->wp2static_options ) ) {
            $err = 'Trying to save an unrecognized option: ' . $name;
            WsLog::l( $err );
            throw new WP2StaticException( $err );
        }

        if ( empty( $value ) ) {
            unset( $this->wp2static_options[ $name ] );
        }

        return $this;
    }

    /**
     *  Set an option
     *
     * @param mixed $value new value for option
     * @throws WP2StaticException
     */
    public function setOption( string $name, $value ) : Options {
        return $this->__set( $name, $value );
    }

    /**
     *  Get an option
     *
     * @return mixed options's value
     */
    public function __get( string $name ) {
        $value = array_key_exists( $name, $this->wp2static_options ) ?
            $this->wp2static_options[ $name ] : null;

        return $value;
    }

    /**
     *  Get an option
     *
     * @return mixed options's value
     */
    public function getOption( string $name ) {
        return $this->__get( $name );
    }

    /**
     *  Get all options
     *
     * @return mixed[] all options
     */
    public function getAllOptions(
        bool $reveal_sensitive_values = false
    ) : array {
        $options_array = array();

        $this->whitelisted_keys = apply_filters(
            'wp2static_whitelist_option_keys',
            $this->whitelisted_keys
        );

        foreach ( $this->wp2static_options_keys as $key ) {

            $value = '*******************';

            if ( in_array( $key, $this->whitelisted_keys ) ) {
                $value = $this->__get( $key );
            } elseif ( $reveal_sensitive_values ) {
                $value = $this->__get( $key );
            }

            $options_array[] = array(
                'Option name' => $key,
                'Value' => $value,
            );
        }

        return $options_array;
    }

    /**
     *  Get all settings (transformed options in alternate format)
     *
     * @return mixed[] all settings
     */
    public function getSettings() : array {
        $settings = [];

        $this->wp2static_options_keys = apply_filters(
            'wp2static_add_option_keys',
            $this->wp2static_options_keys
        );

        foreach ( $this->wp2static_options_keys as $key ) {
            $value = $this->__get( $key );

            $settings[ $key ] = $value;
        }

        /*
            Settings requiring transformation
        */
        $settings['baseUrl'] =
            isset( $settings['baseUrl'] ) ?
            rtrim( $settings['baseUrl'], '/' ) . '/' :
            SiteInfo::getUrl( 'site' );

        return $settings;
    }

    public function optionExists( string $name ) : bool {
        return in_array( $name, $this->wp2static_options_keys );
    }

    public function save() : bool {
        return update_option(
            $this->wp2static_option_key,
            $this->wp2static_options
        );
    }

    public function delete() : bool {
        return delete_option( $this->wp2static_option_key );
    }

    public function saveAllOptions() : void {
        $this->wp2static_options_keys = apply_filters(
            'wp2static_add_option_keys',
            $this->wp2static_options_keys
        );

        foreach ( $this->wp2static_options_keys as $option ) {
            // TODO: set which fields should get which sanitzation upon saving
            // TODO: validate before save & avoid making empty settings fields
            $this->setOption( $option, filter_input( INPUT_POST, $option ) );
            $this->save();
        }
    }
}

