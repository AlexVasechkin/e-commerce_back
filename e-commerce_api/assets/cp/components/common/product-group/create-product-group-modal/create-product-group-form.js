import React, {useState} from 'react';
import axios from 'axios';


const CreateProductGroupForm = ({
  onSuccess
}) => {
  const [isAwait, setIsAwait] = useState(false);

  const [name, setName] = useState('');

  const onSubmit = e => {
    e.preventDefault();

    setIsAwait(true);

    axios
      .post('/api/v1/private/product-group/create', {
        name
      })
      .then(({ data }) => {
        const { id = null } = data;
        onSuccess(id);
      })
      .finally(() => setIsAwait(false));
  };

  const getForm = () => <form onSubmit={ onSubmit }>

    <div className="form-group">
      <label>Название</label>
      <input type="text"
             disabled={ isAwait }
             className="form-control"
             value={ name }
             onChange={ e => setName(e.target.value) }
      />
    </div>

    <div className="form-group text-right">
      <button type={ 'submit' }
              disabled={ isAwait }
              className="btn btn-outline-success"
      >Создать</button>
    </div>
  </form>;

  return getForm();
}

export default CreateProductGroupForm;
