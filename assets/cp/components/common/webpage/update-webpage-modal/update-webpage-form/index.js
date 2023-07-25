import React, {useEffect, useState} from 'react';
import axios from 'axios';


const UpdateWebpageForm = ({
  id,
  onSuccess
}) => {
  const [isAwait, setIsAwait] = useState(false);

  const [name, setName] = useState('');
  const [pagetitle, setPagetitle] = useState('');
  const [headline, setHeadline] = useState('');
  const [description, setDescription] = useState('');
  const [content, setContent] = useState('');
  const [alias, setAlias] = useState('');
  const [isActive, setIsActive] = useState(false);

  const reloadData = id => {
    setIsAwait(true);
    axios
      .post(`/api/v1/private/webpage/${ id }`)
      .then(({ data = {} }) => {
        const { payload = {} } = data;
        const {
          name = '',
          pagetitle = '',
          headline = '',
          description = '',
          content = '',
          alias = '',
          isActive = false
        } = payload;

        setName(name);
        setPagetitle(pagetitle);
        setHeadline(headline);
        setDescription(description);
        setContent(content);
        setAlias(alias);
        setIsActive(isActive);
      })
      .finally(() => setIsAwait(false))
      ;
  };

  useEffect(() => {
    reloadData(id);
  }, []);

  const onSubmit = e => {
    e.preventDefault();

    setIsAwait(true);

    axios
      .post('/api/v1/private/webpage/update', {
        id,
        name,
        pagetitle,
        headline,
        description,
        content,
        alias,
        isActive
      })
      .then(() => {
        onSuccess(id);
      })
      .finally(() => setIsAwait(false))
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

    <div className="form-group">
      <label>Титул страницы</label>
      <input type="text"
             disabled={ isAwait }
             className="form-control"
             value={ pagetitle }
             onChange={ e => setPagetitle(e.target.value) }
      />
    </div>

    <div className="form-group">
      <label>Заголовок</label>
      <input type="text"
             disabled={ isAwait }
             className="form-control"
             value={ headline }
             onChange={ e => setHeadline(e.target.value) }
      />
    </div>

    <div className="form-group">
      <label>Описание</label>
      <textarea disabled={ isAwait }
                className="form-control"
                value={ description }
                onChange={ e => setDescription(e.target.value) }
                rows={ 5 }
      />
    </div>

    <div className="form-group">
      <label>Содержимое</label>
      <textarea disabled={ isAwait }
                className="form-control"
                value={ content }
                onChange={ e => setContent(e.target.value) }
                rows={ 5 }
      />
    </div>

    <div className="form-group">
      <label>Ссылка</label>
      <input type="text"
             disabled={ isAwait }
             className="form-control"
             value={ alias }
             onChange={ e => setAlias(e.target.value) }
      />
    </div>

    <div className="form-group">
      <label><input type="checkbox"
                    disabled={ isAwait }
                    checked={ isActive }
                    onClick={ () => setIsActive(!isActive) }
                    onChange={ e => null }
      /> Активно</label>
    </div>

    <div className="form-group text-right">
      <button type={ 'submit' }
              disabled={ isAwait }
              className="btn btn-outline-success"
      >Сохранить</button>
    </div>

  </form>;

  return getForm();
};

export default UpdateWebpageForm;
