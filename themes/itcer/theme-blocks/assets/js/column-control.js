import assign from 'lodash.assign';

const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { InspectorControls } = wp.editor;
const { PanelBody, SelectControl } = wp.components;
const { addFilter } = wp.hooks;
const { __ } = wp.i18n;

// Enable column control on the following blocks
const enableColumnControlOnBlocks = [
	'core/columns',
];

// Available column control options
const columnControlOptions = [
	{
		label: __( 'Standard' ),
		value: '',
	},
	{
		label: __( 'Spalten Breit - Schmal' ),
		value: 'columns-w-n',
	},
	{
		label: __( 'Spalten Schmal - Breit' ),
		value: 'columns-n-w',
	},
];

/**
 * Add column control attribute to block.
 *
 * @param {object} settings Current block settings.
 * @param {string} name Name of block.
 *
 * @returns {object} Modified block settings.
 */
const addColumnControlAttribute = ( settings, name ) => {
	// Do nothing if it's another block than our defined ones.
	if ( ! enableColumnControlOnBlocks.includes( name ) ) {
		return settings;
	}

	// Use Lodash's assign to gracefully handle if attributes are undefined
	settings.attributes = assign( settings.attributes, {
		column: {
			type: 'string',
			default: columnControlOptions[ 0 ].value,
		},
	} );

	return settings;
};

addFilter( 'blocks.registerBlockType', 'ww-column/attribute/column', addColumnControlAttribute );

/**
 * Create HOC to add column control to inspector controls of block.
 */
const withColumnControl = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		// Do nothing if it's another block than our defined ones.
		if ( ! enableColumnControlOnBlocks.includes( props.name ) ) {
			return (
				<BlockEdit { ...props } />
			);
		}

		const { column } = props.attributes;

		return (
			<Fragment>
				<BlockEdit { ...props } />
				<InspectorControls>
					<PanelBody
						title={ __( 'Spaltenbreiten' ) }
						initialOpen={ true }
					>
						<SelectControl
							label={ __( 'Aufteilung' ) }
							value={ column }
							options={ columnControlOptions }
							onChange={ ( selectedColumnOption ) => {
								props.setAttributes( {
									column: selectedColumnOption,
								} );
							} }
						/>
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	};
}, 'withColumnControl' );

addFilter( 'editor.BlockEdit', 'ww-column/with-column-control', withColumnControl );

/**
 * Add margin style attribute to save element of block.
 *
 * @param {object} saveElementProps Props of save element.
 * @param {Object} blockType Block type information.
 * @param {Object} attributes Attributes of block.
 *
 * @returns {object} Modified props of save element.
 */
const addColumnExtraProps = ( saveElementProps, blockType, attributes ) => {
	// Do nothing if it's another block than our defined ones.
	if ( ! enableColumnControlOnBlocks.includes( blockType.name ) ) {
		return saveElementProps;
	}

		// Use Lodash's assign to gracefully handle if attributes are undefined
		if ( attributes.column ) {
			assign( saveElementProps, { className: saveElementProps.className + ' ' + attributes.column  } );
		}

	return saveElementProps;
};



addFilter( 'blocks.getSaveContent.extraProps', 'ww-column/get-save-content/extra-props', addColumnExtraProps );
