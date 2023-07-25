import {makeAutoObservable, observe} from 'mobx';
import axios from 'axios';


class ProductGroupEditorState {

  productId = null;

  isAwait = false;

  productGroups = [];

  reloadProductGroupList()
  {
    this.isAwait = true;

    axios
        .post('/api/v1/private/product-group-item/list', {productId: this.productId})
        .then(({ data }) => {
          const { payload = [] } = data;
          this.productGroups = payload;
        })
        .finally(() => (this.isAwait = false))
  }

  getOptions() {
    return this.productGroups.map(item => {
      return {
        value: item.id,
        label: item.name
      }
    })
  }

  getValues() {
    return this.productGroups
        .filter(el => el.isInGroup === true)
        .map(item => {
          return {
            value: item.id,
            label: item.name
          }
        })
  }

  createProductGroupItem(productGroupId) {
    axios
        .post('/api/v1/private/product-group-item/create', {
          productId: this.productId,
          productGroupId
        })
    ;
  }

  deleteProductGroupItem(productGroupId) {
    axios
        .post('/api/v1/private/product-group-item/delete', {
          productId: this.productId,
          productGroupId
        })
    ;
  }

  changeInGroup(productGroupId) {
    const idx = this.productGroups.findIndex(el => el.id === productGroupId);
    if (idx > -1) {
      const isInGroup = this.productGroups[idx].isInGroup;

      this.productGroups[idx].isInGroup = !isInGroup;

      if (isInGroup) {
        this.deleteProductGroupItem(productGroupId);
      } else {
        this.createProductGroupItem(productGroupId);
      }
    }
  }

  constructor() {
    makeAutoObservable(this)
  }
}

const productGroupEditorStateInstance = new ProductGroupEditorState();

observe(productGroupEditorStateInstance, change => {
  if (change.name === 'productId') {
    productGroupEditorStateInstance.reloadProductGroupList();
  }
});

export default productGroupEditorStateInstance;
