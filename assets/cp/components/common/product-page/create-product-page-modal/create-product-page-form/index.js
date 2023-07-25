import React, {useState, useMemo} from 'react';
import axios from 'axios';
import cyrToLatTranslit from 'cyrillic-to-translit';


const CreateProductPageForm = ({
  onSuccess = id => {},
  productId
}) => {
  const [id, setId] = useState(null);
  const [name, setName] = useState('');
  const [alias, setAlias] = useState('');

  const translit = useMemo(() => { return new cyrToLatTranslit() }, []);

  const onSubmit = e => {
    e.preventDefault();

    axios
      .post('/api/v1/private/product-page/create', {
        name,
        alias,
        productId
      })
      .then(({ data = {} }) => {
        const { id = null } = data;
        setId(id);
        onSuccess(id);
      })
  };

  const getForm = () => <form onSubmit={ onSubmit }>
    <div className="form-group">
      <label>Внутреннее название</label>
      <input className="form-control"
             value={ name }
             required
             onChange={ e => {
               setName(e.target.value);
               setAlias(translit.transform(e.target.value, '-').toLowerCase())
             }}
      />
    </div>

    <div className="form-group">
      <label>Ссылка</label>
      <input className="form-control"
             value={ alias }
             required
             onChange={ e => setAlias(e.target.value) } />
    </div>

    <div className="text-right">
      <button className="btn btn-outline-success" type="submit">Создать</button>
    </div>

  </form>;

  const getFinishScreen = () => {
    return <div>
      <div className="text-center">Страница успешно создана!</div>
    </div>
  };

  return <>{ id ? getFinishScreen() : getForm() }</>;
};

export default CreateProductPageForm;
