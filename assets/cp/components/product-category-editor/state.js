import {makeAutoObservable} from 'mobx';
import axios from 'axios';


class ProductCategoryEditorState {

  productCategoryDict = []

  productCategories = []

  isAwait = false

  reloadProductCategories() {
    this.isAwait = true;

    axios
      .get('/api/v1/private/product-category/list')
      .then(({ data = {} }) => {
        const { payload = [] } = data;
        console.log(payload);
        this.productCategories = payload;
      })
      .finally(() => this.isAwait = false)
  }

  reloadProductCategoryDict() {
    axios
      .get('/api/v1/private/product-category/dict')
      .then(({ data = {} }) => {
        const { payload = [] } = data;
        this.productCategoryDict = payload.map(item => {
          return {
            value: item.value,
            label: item.caption
          }
        });
      })
  }

  updateDsRow(id, rowData) {
    const idx = this.productCategories.findIndex(el => el.id == id);
    if (idx > -1) {
      this.productCategories[idx].name = rowData.name;
      this.productCategories[idx].parentId = rowData.parentId;
      this.productCategories[idx].isActive = rowData.isActive;
    }
  }

  init() {
    this.reloadProductCategoryDict();
    this.reloadProductCategories();
  }

  constructor() {
    makeAutoObservable(this);
  }
}

const productCategoryEditorStateInstance = new ProductCategoryEditorState();

export default productCategoryEditorStateInstance;
