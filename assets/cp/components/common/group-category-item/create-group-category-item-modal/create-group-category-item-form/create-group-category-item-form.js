import React, { useState } from 'react';
import axios from 'axios';
import Select from 'react-select';


const CreateGroupCategoryItemForm = ({
  onSuccess,
  productGroupDict,
  productCategoryDict,
  initCategoryId
}) => {

  const [productGroupId, setProductGroupId] = useState(null);
  const [productCategoryId, setProductCategoryId] = useState(initCategoryId);
  const [sortOrder, setSortOrder] = useState(500);

  const onSubmit = e => {
    e.preventDefault();

    axios
      .post('/api/v1/private/product-group-category-item/create', {
        productGroupId,
        productCategoryId,
        sortOrder
      })
      .then(() => onSuccess())
  };

  const getValue = (val, dict) => {
    const idx = dict.findIndex(el => el.value === val);
    return idx > -1 ? dict[idx] : null;
  };

  const getForm = () => <form onSubmit={ onSubmit }>
    <div className="form-group" >
      <Select isClearable
              placeholder={ 'Категория' }
              value={ getValue(productCategoryId, productCategoryDict) }
              options={ productCategoryDict }
              onChange={ e => setProductCategoryId(e ? e.value : null) }
      />
    </div>

    <div className="form-group" >
      <Select isClearable
              placeholder={ 'Группа товаров' }
              value={ getValue(productGroupId, productGroupDict) }
              options={ productGroupDict }
              onChange={ e => setProductGroupId(e ? e.value : null) }
      />
    </div>

    <div className="form-group">
      <label>Порядок</label>
      <input className="form-control"
             type="number"
             step={1}
             value={ sortOrder }
             onChange={ e => setSortOrder( parseInt(e.target.value) ) }
      />
    </div>

    <div className="form-group text-right">
      <button className="btn btn-outline-success"
              disabled={ productGroupId === null || productCategoryId === null }
      >Создать</button>
    </div>

  </form>;

  return getForm();
};

export default CreateGroupCategoryItemForm;
