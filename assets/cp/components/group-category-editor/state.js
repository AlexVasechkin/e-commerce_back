import { makeAutoObservable } from 'mobx';
import axios from 'axios';


class GroupCategoryEditorState {

  isAwait = false

  groupCategoryItems = []

  productGroupDict = []

  productCategoryDict = []

  reloadProductGroupCategoryItems() {

    this.isAwait = true;

    axios
      .post('/api/v1/private/product-group-category-item/list')
      .then(({ data = {} }) => {
        const { payload = [] } = data;
        this.groupCategoryItems = payload;
      })
      .finally(() => this.isAwait = false)
  }

  reloadProductGroupDict() {
    axios
      .post('/api/v1/public/product-group/list', {})
      .then(({ data = {} }) => {
        const { payload = [] } = data;
        this.productGroupDict = payload.map(item => {
          return {
            value: item.id,
            label: item.name
          };
        });
      });
  }

  reloadProductCategoryDict() {
    return axios
      .get('/api/v1/private/product-category/dict')
      .then(({ data = {} }) => {
        const { payload = [] } = data;
        this.productCategoryDict = payload.map(item => {
          return {
            value: item.value,
            label: item.caption
          }
        })
      })
  }

  deleteItem(id) {
    axios
      .post('/api/v1/private/product-group-category-item/delete', {
        id
      })
      .then(() => {
        this.reloadProductGroupCategoryItems()
      })
  }

  init() {
    this.reloadProductCategoryDict()
      .then(() => {
        this.reloadProductGroupCategoryItems()
      })

    this.reloadProductGroupDict();
  }

  constructor() {
    makeAutoObservable(this);
  }
}

const groupCategoryEditorStateInstance = new GroupCategoryEditorState();

export default groupCategoryEditorStateInstance;
