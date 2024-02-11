import ShopifyBuy from "@shopify/buy-button-js";

/** Load Events */
jQuery(function () {
  const shopifyClient = ShopifyBuy.buildClient({
    domain: sbbOptions.domain,
    storefrontAccessToken: sbbOptions.token,
  });

  const shopifyUi = ShopifyBuy.UI.init(shopifyClient);

  const options = {
    product: {
      contents: {
        options: false,
      },
      buttonDestination: "modal",
      text: {
        button: "View Product",
      },
    },
    cart: {
      popup: false,
    },
    modalProduct: {
      contents: {
        img: false,
        imgWithCarousel: true,
        button: false,
        buttonWithQuantity: true,
      },
    },
  };

  const shopifyProducts = document.querySelectorAll(".shopify-product");
  if (shopifyProducts.length > 0) {
    shopifyProducts.forEach((el) => {
      shopifyUi.createComponent("product", {
        id: el.dataset.id,
        options: options,
        node: el,
      });
    });
  }

  const shopifyCollections = document.querySelectorAll(".shopify-collection");
  if (shopifyCollections.length > 0) {
    shopifyCollections.forEach((el) => {
      shopifyUi.createComponent("collection", {
        id: el.dataset.id,
        options: options,
        node: el,
      });
    });
  }
});
