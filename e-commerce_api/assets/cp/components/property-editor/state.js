import {makeAutoObservable} from 'mobx';
import axios from 'axios';


class PropertyEditorState {

  isAwait = false

  dataSet = []

  reloadDataSet() {
    this.isAwait = true;

    axios
      .post('/api/v1/private/property/list')
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

  constructor() {
    makeAutoObservable(this);
  }

  init() {
    this.reloadDataSet()
  }
}

const propertyEditorStateInstance = new PropertyEditorState();

export default propertyEditorStateInstance;
