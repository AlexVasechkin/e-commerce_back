import React from 'react';
import ReactDOM from 'react-dom';
import {observer} from 'mobx-react-lite';

import groupCategoryEditorStateInstance from './state';
import GroupCategoryEditorCreateItemModal from './components/create-group-category-item-modal';


const GroupCategoryEditor = observer(() => {
  return (
    <>
      <div className="row">
        <div className="col-12">
          <div className="card m-b-30">
            <div className="card-body">
              <GroupCategoryEditorCreateItemModal productGroupDict={ groupCategoryEditorStateInstance.productGroupDict }
                                                  productCategoryDict={ groupCategoryEditorStateInstance.productCategoryDict }
                                                  initCategoryId={ null }
              />
            </div>
          </div>
        </div>
      </div>

      <div className="row">
      { Array
          .from(new Set(groupCategoryEditorStateInstance.groupCategoryItems.map(item => item.productCategory.id)))
          .map(categoryId => {

            const catIdx = groupCategoryEditorStateInstance.productCategoryDict.findIndex(el => el.value === categoryId);

            return (
              <div className="col-12 col-sm-3"
                   key={ `ci-${ groupCategoryEditorStateInstance.productCategoryDict[catIdx].value }` }>
                <div className="card m-b-30">
                  <div className="card-header">
                    <h5 className="card-title">{ `${ groupCategoryEditorStateInstance.productCategoryDict[catIdx].label }` }</h5>
                  </div>

                  <div className="card-body">
                    <div className="row">
                      <div style={ { paddingLeft: '15px' } }>
                        <div className="col-12">
                          {
                            groupCategoryEditorStateInstance.groupCategoryItems
                              .filter(el => el.productCategory.id === groupCategoryEditorStateInstance.productCategoryDict[catIdx].value)
                              .sort(function (a, b) {
                                if (a.sortOrder === b.sortOrder) {
                                  return 0;
                                }

                                return a.sortOrder > b.sortOrder ? 1 : -1;

                              })
                              .map(item => {
                                return (
                                  <div className="row">
                                    <div className="col-12 m-b-10">
                                      <div style={ { display: 'table' } } >
                                        <div style={ { display: 'table-cell' } }>
                                          <button className="btn btn-outline-light"
                                                  onClick={ () => groupCategoryEditorStateInstance.deleteItem(item.id) }
                                          ><i className="fa fa-trash" /></button>
                                        </div>

                                        <div style={ { paddingLeft: '10px' } }>{ `${ item.sortOrder }` } <span style={ { fontWeight: 700 } }>{ `${ item.productGroup.name }` }</span></div>

                                      </div>
                                    </div>
                                  </div>
                                )
                              })
                          }
                        </div>
                      </div>
                    </div>

                    <div className="row">
                      <div className="col-12 text-right">
                        <GroupCategoryEditorCreateItemModal productGroupDict={ groupCategoryEditorStateInstance.productGroupDict }
                                                            productCategoryDict={ groupCategoryEditorStateInstance.productCategoryDict }
                                                            initCategoryId={ categoryId }
                        />
                      </div>
                    </div>

                  </div>

                </div>
              </div>
            )
          })
      }
      </div>
    </>
  )
});

const renderGroupCategoryEditor = () => {
  if (document.getElementById('group-category-editor-root')) {
    groupCategoryEditorStateInstance.init();
    ReactDOM.render(<GroupCategoryEditor />, document.getElementById('group-category-editor-root'));
  }
};

export default renderGroupCategoryEditor;
