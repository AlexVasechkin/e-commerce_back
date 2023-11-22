import {makeAutoObservable, observe} from 'mobx';
import axios from 'axios';


class ProductImageEditorState {

  id = null

  isAwait = false

  images = []

  constructor() {
    makeAutoObservable(this);
    this.reloadImages();
  }

  reloadImages() {
    if (this.id === null) {
      return;
    }

    this.isAwait = true;

    axios
      .get(`/api/v1/private/product-images/${ this.id }`)
      .then(({ data = {} }) => {
        const { payload = [] } = data;
        this.images = payload;
      })
      .finally(() => {
        this.isAwait = false;
      });
  }

  update(payload) {
    axios
      .post('/api/v1/private/product-image/update', payload)
      .finally(() => {
        this.reloadImages();
      })
  }
}

const productImageEditorStateInstance = new ProductImageEditorState();
observe(productImageEditorStateInstance, change => {
  if (change.name === 'id') {
    productImageEditorStateInstance.reloadImages();
  }
});

export default productImageEditorStateInstance;
