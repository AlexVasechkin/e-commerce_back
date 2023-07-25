import {makeAutoObservable, observe} from 'mobx';
import axios from 'axios';


class PropertyEditorState {

  id = null

  isAwait = false

  properties = []

  constructor() {
    makeAutoObservable(this)
  }

  reloadProperties() {
    if (this.id) {
      this.isAwait = true;

      axios
        .get(`/api/v1/private/product-property-values/${ this.id }`)
        .then(({ data = {} }) => {
          const { payload = [] } = data;
          this.properties = payload;
        })
        .finally(() => this.isAwait = false)
    }
  }

  save({ productId = 0, propertyId = 0, value = null }) {
    axios
      .post('/api/v1/private/product-property-value/save', {
        productId,
        propertyId,
        value
      })
    ;
  }
}

const propertyEditorStateInstance = new PropertyEditorState();
observe(propertyEditorStateInstance, change => {
  if (change.name === 'id') {
    propertyEditorStateInstance.reloadProperties();
  }
});

export default propertyEditorStateInstance;
