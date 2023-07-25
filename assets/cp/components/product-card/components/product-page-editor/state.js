import {makeAutoObservable, observe} from 'mobx';
import axios from 'axios';


class ProductPageEditorState {

  productId = null;

  dataSet = null;

  isAwait = false;

  constructor() {
    makeAutoObservable(this);
  }

  reloadPageData() {
      this.isAwait = true;

      axios
        .get(`/api/v1/private/product-page-by-product/${ this.productId }`)
        .then(({ data = {} }) => {
          const { payload = {} } = data;
          this.dataSet = payload;
        })
        .finally(() => this.isAwait = false)
      ;
  }

  update() {
    this.isAwait = true;

    axios
      .post('/api/v1/private/product-page/update', this.dataSet)
      .then(() => {
        this.reloadPageData();
      })
      .finally(() => this.isAwait = false)
  }
}

const productPageEditorStateInstance = new ProductPageEditorState();

observe(productPageEditorStateInstance, change => {
  if (change.name === 'productId') {
    productPageEditorStateInstance.reloadPageData();
  }
});


export default productPageEditorStateInstance;
