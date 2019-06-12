/**
 * BLOCK: ww/group
 */

import classNames from 'classnames'; // Used to to join classes together

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const {
	Fragment, // Used to wrap our edit component and only have one root element
} = wp.element;
const {
	InnerBlocks, // Allows it to place child blocks inside our block
	InspectorControls, // We place our select control inside the inspector contorls which show up on the right of the editor
  PanelColorSettings,
} = wp.editor;
const {
	PanelBody, // A panel where we place our select control in (creates a colapsable element)
	TextControl,
	SelectControl, // Our select control to choose the background color
} = wp.components;

registerBlockType( 'ww/group', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'Container', 'ww' ), // Block title.
	icon: 'editor-table', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'layout', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'Background', 'ww' ),
		__( 'Wrapper Block', 'ww' ),
	],

	attributes: {
		// Register cHeight attribute to save the chosen color
		linkurl: {
			type: 'string',
		},
    color: {
			type: 'string',
		},
		cHeight: {
			type: 'string',
		},
    cBorder: {
      type: 'string',
    },
	},

	edit( { attributes, setAttributes, className } ) {
		const {
			cHeight = '',
		} = attributes;
    const {
      cBorder = '',
    } = attributes;
    const {
      color = '',
    } = attributes;
		const {
			linkurl = '',
		} = attributes;

		return (
			<Fragment>
				<InspectorControls>
            <PanelColorSettings
              title={ __( 'Color Settings' ) }
              colorSettings={ [
                {
                  value: color,
                  onChange: ( colorValue ) => setAttributes( { color: colorValue } ),
                  label: __( 'Background Color' ),
                }
              ] }
            />
          <PanelBody
						title={ __( 'Funktionen', 'ww' ) }
						initialOpen={ true }
					>
					<SelectControl
						label={ __( 'Höhe', 'ww' ) }
						value={ cHeight }
						options={ [
							{
								value: '',
								label: __( 'Standard', 'ww' ),
							},
							{
								value: 'height-2',
								label: __( 'Doppelte Höhe', 'ww' ),
							},
							{
								value: 'height-1',
								label: __( 'Einfache Höhe', 'ww' ),
							},
						] }
						onChange={ ( selectedOption ) => setAttributes( { cHeight: selectedOption } ) }
						/>
            <SelectControl
							label={ __( 'Rahmen', 'ww' ) }
							value={ cBorder }
							options={ [
								{
									value: '',
									label: __( 'Kein Rahmen unten', 'ww' ),
								},
								{
									value: 'has-bottom-border bottom-border-peach',
									label: __( 'Rahmen unten Peach', 'ww' ),
								},
								{
									value: 'has-bottom-border bottom-border-cyan',
									label: __( 'Rahmen unten Cyan', 'ww' ),
								},
								{
									value: 'has-bottom-border bottom-border-darkblue',
									label: __( 'Rahmen unten Dunkelblau', 'ww' ),
								},
							] }
							onChange={ ( selectedOption ) => setAttributes( { cBorder: selectedOption } ) }
						/>
						<TextControl
            	label='Linkziel'
            	value={ linkurl }
            	onChange={ ( value ) => setAttributes( { linkurl: value } ) }
        		/>
          </PanelBody>
				</InspectorControls>
				<div
					className={ className }
					style={ { backgroundColor: color } }
				>
					<InnerBlocks />
				</div>
			</Fragment>
		);
	},

	save( { attributes, className } ) {
		const {
			cHeight = '',
		} = attributes;
    const {
      cBorder = '',
    } = attributes;
    const {
      color = '',
    } = attributes;
		const {
      linkurl = '',
    } = attributes;

		let styles = {};
		let classes = className;

		// Only set attributes when background color is chosen
		if ( '' !== color ) {
			styles = { backgroundColor: color };
			// Use classnames library to join all classes together
			classes = classNames( `has-background`, classes );
		}
    if ( '' !== cHeight ) {
      classes = classNames( cHeight, classes );
    }
    if ( '' !== cBorder ) {
      classes = classNames( cBorder, classes );
    }

		if ( '' !== linkurl ) {
			return (
				<div
					className={ classes }
					style={ styles }
				>
					<a href={ linkurl }>
						<InnerBlocks.Content />
					</a>
				</div>
			);
		}

		return (
			<div
				className={ classes }
				style={ styles }
			>
				<InnerBlocks.Content />
			</div>
		);
	},
} );
