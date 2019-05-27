const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;
const { RangeControl, SelectControl } = wp.components;

registerBlockType("restaurant/related-products", {
  title: __("Related products"),
  icon: "products",
  category: "common",

  attributes: {
    perPage: {
      type: "number",
      default: 8
    },
    algorithm: {
      type: "string",
      default: "keyword"
    }
  },

  edit: props => {
    const {
      className,
      attributes: { perPage, algorithm },
      setAttributes
    } = props;

    return (
      <div className={className}>
        <RangeControl
          label={__("Products per page")}
          onChange={perPage => setAttributes({ perPage })}
          value={perPage}
        />
        <SelectControl
          label={__("Related algorithm")}
          onChange={algorithm => setAttributes({ algorithm })}
          options={[
            { value: "keyword", label: __("Keyword") },
            { value: "taxonomy", label: __("Taxonomy") }
          ]}
          value={algorithm}
        />
      </div>
    );
  },
  save: () => {
    return null;
  }
});
