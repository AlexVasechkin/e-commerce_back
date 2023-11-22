import React, {useState} from 'react';
import axios from 'axios';


const CreateCategoryPageForm = ({
  categoryId,
  onSuccess = () => null
}) => {
  const [id, setId] = useState(null);
  const [name, setName] = useState('');
  const [alias, setAlias] = useState('');
  const [isAwait, setIsAwait] = useState(false);

  const onSubmit = e => {
    e.preventDefault();

    setIsAwait(true);

    axios
      .post('/api/v1/private/category-page/create', {
        categoryId,
        name,
        alias
      })
      .then(({ data = {} }) => {
        const { id = null } = data;
        setId(id);
        onSuccess();
      })
      .finally(() => setIsAwait(false))
  };

  const getForm = () => <form onSubmit={ onSubmit }>
    <div className={ 'form-group' }>
      <label>Внутреннее название</label>
      <input type="text"
             className={ 'form-control' }
             disabled={ isAwait }
             value={ name }
             onChange={ e => setName(e.target.value) }
      />
    </div>

    <div className={ 'form-group' }>
      <label>Ссылка</label>
      <input type="text"
             className={ 'form-control' }
             disabled={ isAwait }
             value={ alias }
             onChange={ e => setAlias(e.target.value) }
      />
    </div>

    <div className={ 'form-group text-right' }>
      <button type={ 'submit' }
              disabled={ isAwait }
              className={ 'btn btn-outline-success' }
      >Создать</button>
    </div>

  </form>;

  const getFinishScreen = () => {
    return <div className={'text-center'}>Готово!</div>
  };

  return id ? getFinishScreen() : getForm();
};

export default CreateCategoryPageForm;
