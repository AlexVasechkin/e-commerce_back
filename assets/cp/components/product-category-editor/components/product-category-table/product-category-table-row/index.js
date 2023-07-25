import React, {useEffect, useState} from 'react';
import Select from 'react-select';
import axios from 'axios';
import ProductCategoryEditorCreateCategoryPageModal from '../../create-category-page-modal';
import ProductCategoryEditorUpdateCategoryPageModal from '../../update-category-page-modal';
import UploadProductCategoryImageModal from './components/upload-product-category-image-modal';


const ProductCategoryTableRow = ({
  row = {
    id: 0,
    name: '',
    nameSingle: '',
    parentId: 0,
    isActive: false,
    webpageId: null,
    picture: null
  },
  productCategoryDict = [],
  updateDsRow = (id, data) => {}
}) => {

  const { id = null } = row;
  const [isAwait, setIsAwait] = useState(false);
  const [name, setName] = useState();
  const [nameSingle, setNameSingle] = useState();
  const [parentId, setParentId] = useState(null);
  const [isActive, setIsActive] = useState(false);

  const setInitialValues = () => {
    setName(row.name ?? '');
    setNameSingle(row.nameSingle ?? '');
    setParentId(row.parentId ?? null);
    setIsActive(row.isActive ?? false);
  };

  useEffect(() => {
    setInitialValues();
  }, []);

  const save = () => {
    setIsAwait(true);

    axios
      .post('/api/v1/private/product-category/update', {
        id,
        name,
        nameSingle,
        parentId: parentId ?? 0,
        isActive
      })
      .then(() => {
        updateDsRow(id, {
          name,
          nameSingle,
          parentId,
          isActive
        })
      })
      .catch(() => setInitialValues())
      .finally(() => setIsAwait(false))
  };

  const getParentSelectComponent = () =>
    <Select options={ productCategoryDict }
            isDisabled={ isAwait }
            isClearable
            placeholder="Нет"
            value={ parentId ? productCategoryDict.find(el => el.value == parentId) : null }
            onChange={ v => setParentId(v ? parseInt(v.value) : v) }
    />;

  return <tr>
    <td>{ `${ id ?? '' }` }</td>
    <td>{ getParentSelectComponent() }</td>
    <td>
      <input type="text"
             className="form-control"
             value={ `${ name }` }
             disabled={ isAwait }
             onChange={ e => setName(e.target.value) }
      />
    </td>
    <td>
      <input type="text"
             className="form-control"
             value={ `${ nameSingle }` }
             disabled={ isAwait }
             onChange={ e => setNameSingle(e.target.value) }
      />
    </td>
    <td>
      <label>
        <input type="checkbox"
               disabled={ isAwait }
               checked={ isActive }
               onClick={ () => setIsActive(!isActive) }
               onChange={ () => null }
        /> Активно
      </label>
    </td>
    <td>{   row.webpageId
          ? <ProductCategoryEditorUpdateCategoryPageModal id={ row.webpageId } />
          : <ProductCategoryEditorCreateCategoryPageModal categoryId={ id } />
        }
    </td>
    <td>{   row.picture
          ? <img style={ {maxHeight: '100px'} } src={ row.picture } alt={''} />
          : <UploadProductCategoryImageModal categoryId={row.id} />
    }</td>
    <td>
      <div className="btn-group">
        <button disabled={ isAwait }
                className="btn btn-outline-success"
                onClick={ () => save() }><i className="fa fa-save" /></button>
        {/*<button disabled={ isAwait }*/}
        {/*        className="btn btn-outline-light"*/}
        {/*        onClick={ () => setInitialValues() }><i className="fa fa-refresh" /></button>*/}
      </div>
    </td>
  </tr>;
};

export default ProductCategoryTableRow;
