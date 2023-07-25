import React, {useState, useEffect} from 'react';
import axios from 'axios';


const UpdateProductGroupForm = ({
  id,
  onSuccess
}) => {

  const [isAwait, setIsAwait] = useState(false);

  const [_id, setId] = useState(null);
  const [_name, setName] = useState('');
  const [_isToHomepage, setIsToHomepage] = useState(false);
  const [_homepageSort, setHomepageSort] = useState(-1);

  const reloadData = () => {

    setIsAwait(true);

    axios
      .get(`/api/v1/private/product-group/${ id }`)
      .then(({ data = {} }) => {
        const { payload = {} } = data;

        const {
          name = '',
          isToHomepage = false,
          homepageSort = -1,
        } = payload;

        const extractId = (payload) => {
          const { id = null } = payload;
          return id;
        }

        setId(extractId(payload));
        setName(name);
        setIsToHomepage(isToHomepage);
        setHomepageSort(homepageSort);
      })
      .finally(() => setIsAwait(false))
    ;
  };

  useEffect(() => {
    reloadData();
  }, []);

  const validateSort = (newVal, fallbackValue) => {
    try {
      const val =  parseInt(newVal);
      if (val > -2) {
        return val;
      } else {
        return fallbackValue;
      }
    } catch (e) {
      return fallbackValue;
    }
  };

  const onSubmit = e => {
    e.preventDefault();

    if (_id === null) {
      return;
    }

    setIsAwait(true);

    axios
      .post('/api/v1/private/product-group/update', {
        id: _id,
        name: _name,
        isToHomepage: _isToHomepage,
        homepageSort: _homepageSort
      })
      .then(() => {
        onSuccess();
      })
      .finally(() => setIsAwait(false))
  };

  const getForm = () => <form onSubmit={ onSubmit }>
    <div className="form-group">
      <label>Название</label>
      <input type="text"
             disabled={ !(!isAwait && (_id !== null)) }
             className="form-control"
             value={ _name }
             onChange={ e => setName(e.target.value) }
      />
    </div>

    <div className="form-group">
      <label><input type="checkbox"
                    disabled={ !(!(!isAwait && (_id !== null))) }
                    checked={ _isToHomepage }
                    onClick={ () => setIsToHomepage(!_isToHomepage) }
                    onChange={ e => null }
      /> на главную</label>
    </div>

    <div className="form-group">
      <label>Приоритет на главной</label>
      <input type="number"
             step={ 1 }
             disabled={ !(!(!isAwait && (_id !== null))) }
             className="form-control"
             value={ _homepageSort }
             onChange={ e => setHomepageSort(validateSort(e.target.value, _homepageSort)) }
      />
    </div>

    <div className="form-group text-right">
      <button type={ 'submit' }
              disabled={ !(!(!isAwait && (_id !== null))) }
              className={ ['btn', 'btn-outline-success'].join(' ').trim() }
      >Сохранить</button>
    </div>
  </form>;

  return getForm();
}

export default UpdateProductGroupForm;
