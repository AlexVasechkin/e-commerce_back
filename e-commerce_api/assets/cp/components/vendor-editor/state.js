import {makeAutoObservable} from 'mobx';
import axios from 'axios';


class VendorEditorState {

  dataSet = [];

  isAwait = false;

  reloadDataSet = () => {

    this.isAwait = true;

    axios
      .post('/api/v1/private/vendors', {})
      .then(({ data = {} }) => {
        const { payload = [] } = data;
        this.dataSet = payload;
      })
      .finally(() => {
        this.isAwait = false;
      })
  };

  constructor() {
    makeAutoObservable(this);
  }

  init() {
    this.reloadDataSet();
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
}

const vendorEditorStateInstance = new VendorEditorState()

export default vendorEditorStateInstance;
