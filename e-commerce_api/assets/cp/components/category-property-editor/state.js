import {makeAutoObservable, observe} from 'mobx';
import axios from 'axios';


class CategoryPropertyEditorState {

  isAwait = false

  dataSet = []

  categories = []

  properties = []

  filters = {
    productCategoryIdList: []
  }

  reloadCategories() {
    axios
      .get('/api/v1/private/product-category/dict')
      .then(({ data = {} }) => {
        const { payload = [] } = data;
        this.categories = payload.map(item => {
          return {
            value: item.value,
            label: item.caption
          };
        });
      });
  }

  reloadDataSet() {

    if (this.filters.productCategoryIdList === []) {
      return;
    }

    this.isAwait = true;

    axios
      .post('/api/v1/private/category-property/list', {...this.filters})
      .then(({ data = {} }) => {
        const { payload = [] } = data;
        this.dataSet = payload;
      })
      .finally(() => {
        this.isAwait = false;
      })
  }

  removeDsItem(id) {
    const idx = this.dataSet.findIndex(el => el.id == id);

    if (idx > -1) {
      this.dataSet = [
        ...this.dataSet.splice(0, idx),
        ...this.dataSet.splice(idx + 1)
      ];
    }
  }

  reloadProperties() {
    axios
      .get('/api/v1/private/property/dict')
      .then(({ data = {} }) => {
        const { payload = [] } = data;
        this.properties = payload.map(item => {
          return {
            value: item.value,
            label: item.caption
          };
        });
      });
  }

  constructor() {
    makeAutoObservable(this);
  }

  init() {
    this.reloadCategories();
    this.reloadProperties();
  }
}

const categoryPropertyEditorStateInstance = new CategoryPropertyEditorState();

observe(categoryPropertyEditorStateInstance, change => {
  if (change.name === 'filters') {
    categoryPropertyEditorStateInstance.reloadDataSet();
  }
});

export default categoryPropertyEditorStateInstance;
