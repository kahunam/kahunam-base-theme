( function () {
	var el = wp.element.createElement;
	var PluginDocumentSettingPanel = wp.editPost.PluginDocumentSettingPanel;
	var CheckboxControl = wp.components.CheckboxControl;
	var useSelect = wp.data.useSelect;
	var useDispatch = wp.data.useDispatch;
	var registerPlugin = wp.plugins.registerPlugin;

	var elements = [
		{ key: '_kahu_disable_top_bar', label: 'Top Bar' },
		{ key: '_kahu_disable_header', label: 'Header' },
		{ key: '_kahu_disable_primary_navigation', label: 'Primary Navigation' },
		{ key: '_kahu_disable_featured_image', label: 'Featured Image' },
		{ key: '_kahu_disable_content_title', label: 'Content Title' },
		{ key: '_kahu_disable_footer', label: 'Footer' },
	];

	function DisableElementsPanel() {
		var meta = useSelect( function ( select ) {
			return select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {};
		} );

		var editPost = useDispatch( 'core/editor' ).editPost;

		function toggleMeta( key ) {
			var update = {};
			update[ key ] = ! meta[ key ];
			editPost( { meta: update } );
		}

		return el(
			PluginDocumentSettingPanel,
			{
				name: 'kahu-disable-elements',
				title: 'Disable Elements',
				icon: 'visibility',
			},
			elements.map( function ( item ) {
				return el( CheckboxControl, {
					key: item.key,
					label: item.label,
					checked: !! meta[ item.key ],
					onChange: function () {
						toggleMeta( item.key );
					},
				} );
			} )
		);
	}

	registerPlugin( 'kahu-disable-elements', {
		render: DisableElementsPanel,
	} );
} )();
