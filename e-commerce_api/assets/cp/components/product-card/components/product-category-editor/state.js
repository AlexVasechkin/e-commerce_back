import {makeAutoObservable, observe} from 'mobx';
import axios from 'axios';
import propertyEditorStateInstance from '../property-editor/state';


class ProductCategoryEditorState {

  productId = null

  productCategoryId = null

  categoryItemId = null

  isAwait = false

  constructor() {
    makeAutoObservable(this);
  }

  reloadProductCategory() {
    if (this.productId) {
      this.isAwait = true;

      axios
        .post('/api/v1/private/product-category-item/find-by-product', { productId: this.productId })
        .then(({ data = {} }) => {
          const {
            productCategoryId = null,
            categoryItemId = null
          } = data;
          this.productCategoryId = productCategoryId;
          this.categoryItemId = categoryItemId;
          propertyEditorStateInstance.reloadProperties();
        })
        .finally(() => this.isAwait = false);
    }
  }

  updateCategoryItem() {
    this.isAwait = true;

    axios
      .post('/api/v1/private/product-category-item/update', {
        categoryItemId: this.categoryItemId,
        productCategoryId: this.productCategoryId
      })
      .then(() => this.reloadProductCategory())
      .finally(() => this.isAwait = false)
  }

  removeCategoryItem() {
    this.isAwait = true;

    axios
      .post('/api/v1/private/product-category-item/delete', {id: this.categoryItemId})
      .then(() => {
        this.categoryItemId = null;
        this.reloadProductCategory();
      })
      .finally(() => this.isAwait = false)
  }

  createCategoryItem() {
    this.isAwait = true;

    axios
      .post('/api/v1/private/product-category-item/create', {
        productId: this.productId,
        categoryId: this.productCategoryId
      })
      .then(() => this.reloadProductCategory())
      .finally(() => this.isAwait = false)
  }

  setProductCategoryId(id) {
    this.productCategoryId = id;

    if (id === null) {
      if (this.categoryItemId) {
        this.removeCategoryItem();
      }
    } else {
      if (this.categoryItemId === null) {
        this.createCategoryItem();
      } else {
        this.updateCategoryItem();
      }
    }
  }
}

const productCategoryEditorStateInstance = new ProductCategoryEditorState();

observe(productCategoryEditorStateInstance, change => {
  if (change.name === 'productId') {
    productCategoryEditorStateInstance.reloadProductCategory();
  }
});

export default productCategoryEditorStateInstance;
