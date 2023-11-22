import React from 'react';
import {observer} from 'mobx-react-lite';
import ReactDOM from 'react-dom';
import categoryPropertyEditorStateInstance from './state';
import {Spinner} from 'react-bootstrap';
import Select from 'react-select';
import CategoryPropertyTable from './components/category-property-table';
import CategoryPropertyEditorCreateModal from './components/create-category-property-modal';
import CategoryPropertyEditorCreatePropertyModal from './components/create-property-modal';


const CategoryPropertyEditor = observer(() => {

  const getCategorySelectValue = () => {
    return categoryPropertyEditorStateInstance.filters.productCategoryIdList;
  };

  const setFilterValue = (key, value) => {
    const filters = {...categoryPropertyEditorStateInstance.filters};
    filters[key] = value;
    categoryPropertyEditorStateInstance.filters = filters;
  };

  return <div className="row">
    <div className="col-lg-12">
      <div className="card m-b-30">
        <div className="card-header">
          <div className="row align-items-center">
            <div className="col-6">
              <h5 className="card-title mb-0">Свойства категорий</h5>
            </div>
          </div>
        </div>

        <div className="card-body">
          <div className="text-right m-b-15">
            <CategoryPropertyEditorCreateModal properties={ categoryPropertyEditorStateInstance.properties } /> <CategoryPropertyEditorCreatePropertyModal />
          </div>
          <hr/>
          <div className="m-b-15">
            <div className="col-sm-3">
              <Select options={ categoryPropertyEditorStateInstance.categories }
                      value={ getCategorySelectValue() }
                      isMulti
                      isClearable
                      placeholder={ 'Категория' }
                      onChange={ v => setFilterValue('productCategoryIdList', v) }
              />
            </div>
          </div>
          <hr/>
          <div>
            { categoryPropertyEditorStateInstance.isAwait
              ? <div className="text-center"><Spinner animation={ 'border' } /></div>
              : <CategoryPropertyTable dataSet={ categoryPropertyEditorStateInstance.dataSet } /> }
          </div>

        </div>
      </div>
    </div>
  </div>
});

const renderCategoryPropertyEditor = () => {
  const root = document.getElementById('category-property-editor-root');
  if (root !== null) {
    categoryPropertyEditorStateInstance.init();
    ReactDOM.render(<CategoryPropertyEditor />, root);
  }
};

export default renderCategoryPropertyEditor;
