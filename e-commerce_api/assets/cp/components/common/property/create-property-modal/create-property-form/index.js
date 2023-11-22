import React, {useState} from 'react';
import axios from 'axios';


const CreatePropertyForm = ({
  onSuccess = id => {}
}) => {
  const [id, setId] = useState(null);
  const [name, setName] = useState('');

  const onSubmit = e => {
    e.preventDefault();

    axios
      .post('/api/v1/private/property/create', {name})
      .then(({ data = {} }) => {
        const { id = null } = data;
        setId(id);
        onSuccess(id);
      })
  };

  const resetForm = () => {
    setId(null);
    setName('');
  };

  const getForm = () => <form onSubmit={ onSubmit }>
    <div className="form-group">
      <label>Свойство</label>
      <input className="form-control" value={ name } onChange={ e => setName(e.target.value) } />
    </div>

    <div className="text-right">
      <button className="btn btn-outline-success" type="submit">Создать</button>
    </div>

  </form>;

  const getFinishScreen = () => {
    return <div>
      <div className="text-center">Свойство успешно добавлено!</div>
      <div className="text-center"><button className="btn btn-outline-info" onClick={ resetForm }>Добавить еще</button></div>
    </div>
  };

  return <>{ id ? getFinishScreen() : getForm() }</>
};

export default CreatePropertyForm;
