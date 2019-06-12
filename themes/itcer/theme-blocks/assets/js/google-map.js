/**
 * BLOCK: Webwerk - Google Map
 *
 * Registering a basic block with Gutenberg.
 */

//  Import CSS.
// import './style.scss';
// import './editor.scss';

// const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const {
	Fragment // Used to wrap our edit component and only have one root element
} = wp.element;
const { InspectorControls } = wp.editor;
const { TextControl } = wp.components;


/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'ww/google-map', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: 'Google Map', // Block title.
	icon: 'location-alt', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		'Google Map',
		'Webwerk',
	],
  attributes: {
            // To storage text colour of the button
            name: {
                type: 'string',
                default: '', // Default value for newly added block
            },
            address: {
                type: 'string',
                default: '', // Default value for newly added block
            },
            place: {
                type: 'string',
                default: '', // Default value for newly added block
            }
},

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	edit( props ) {

    const {
      className,
			attributes,
      attributes: {
				name,
        address,
				place,
        key,
      },
      setAttributes,
    } = props;

    const onChangeName = ( content ) => {
        setAttributes( { name: content } );
    }

    const onChangeAddress = ( content ) => {
        setAttributes( { address: content } );
    }

    const onChangePlace = ( content ) => {
        setAttributes( { place: content } );
    }

    const onChangeKey = ( content ) => {
        setAttributes( { key: content } );
    }

		return (
      <Fragment>
        <InspectorControls>
          <TextControl
						label='Name'
            onChange={onChangeName} // onChange event callback
            value={name} // Input Binding
            />
          <TextControl
						label='Adresse'
            onChange={onChangeAddress} // onChange event callback
            value={address} // Input Binding
            />
          <TextControl
						label='PLZ/Ort'
            onChange={onChangePlace} // onChange event callback
            value={place} // Input Binding
            />
          <TextControl
						label='Google Maps API Key'
            onChange={onChangeKey} // onChange event callback
            value={key} // Input Binding
            />
        </InspectorControls>
        <h2>Google Map</h2>
      </Fragment>
    );
	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save( props ) {

    const {
      className,
			attributes,
      attributes: {
				name,
        address,
				place,
        key,
      },
    } = props;

    let map_str = name + "+" + address + "+" + place;
    map_str = map_str.replace(/ /g, "+");

    return (
      <div className="google-map" data-gmap-url={`https://www.google.com/maps/embed/v1/place?key=${key}&amp;q=${map_str}`}>
        <iframe class="gmap-iframe" src=""></iframe>
      </div>
    );
}
} );
