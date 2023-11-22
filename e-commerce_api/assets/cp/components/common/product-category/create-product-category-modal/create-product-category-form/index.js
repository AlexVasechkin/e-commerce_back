import React, {useState} from 'react';
import Select from 'react-select';
import axios from 'axios';


const CreateProductCategoryForm = ({
  onSuccess,
  productCategoryDict
}) => {
  const [id, setId] = useState(null);
  const [name, setName] = useState('');
  const [nameSingle, setNameSingle] = useState('');
  const [parentId, setParentId] = useState(null);
  const [isAwait, setIsAwait] = useState(false);

  const onSubmit = e => {
    e.preventDefault();

    setIsAwait(true);

    axios
      .post('/api/v1/private/product-category/create', {
        name,
        nameSingle,
        parentId
      })
      .then(({ data = {} }) => {
        const { id = null } = data;
        setId(id);
        onSuccess(id);
      })
      .finally(() => setIsAwait(false))
    ;
  };

  const getForm = () => <form onSubmit={ onSubmit }>
    <div className="form-group">
      <label>Название</label>
      <input type="text"
             className={ 'form-control' }
             value={ `${ name }` }
             disabled={ isAwait }
             onChange={ e => setName(e.target.value) }
      />
    </div>

    <div className="form-group">
      <label>В ед. числе</label>
      <input type="text"
             className={ 'form-control' }
             value={ `${ nameSingle }` }
             disabled={ isAwait }
             onChange={ e => setNameSingle(e.target.value) }
      />
    </div>

    <div className="form-group">
      <label>Родительская категория</label>
      <Select options={ productCategoryDict }
              value={ parentId ? productCategoryDict.find(el => el.id == parentId) : null }
              onChange={ e => setParentId(e ? parseInt(e.value) : e) }
              placeholder={ 'Родительская категория' }
              isClearable
      />
    </div>

    <div className="form-group">
      <button type="submit"
              disabled={ isAwait }
              className="btn btn-outline-success">Создать</button>
    </div>

  </form>;

  const resetForm = () => {
    setId(null);
    setName('');
    setNameSingle('');
    setParentId(null);
  };

  const getFinishScreen = () => <div>
      <div className="text-center">Категория успешно добавлена!</div>
      <div className="text-center">
        <button className="btn btn-outline-info"
                onClick={ resetForm }>Добавить еще</button>
      </div>
    </div>
  ;

  return id ? getFinishScreen() : getForm();
};

export default CreateProductCategoryForm;
