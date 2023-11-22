import React, {useState} from 'react';
import axios from 'axios';
import Select from 'react-select';


const CreateCategoryPropertyForm = ({
  categories = [],
  properties = [],
  onSuccess = id => {}
}) => {
  const [id, setId] = useState(null);
  const [isAwait, setIsAwait] = useState(false);
  const [category, setCategory] = useState(null);
  const [property, setProperty] = useState(null);
  const [groupName, setGroupName] = useState('');
  const [type, setType] = useState(null);
  const [unit, setUnit] = useState('');

  const types = [
    {
      value: 'string',
      label: 'Строковый'
    },
    {
      value: 'int',
      label: 'Целочисленный'
    },
    {
      value: 'float',
      label: 'Дробное число'
    },
    {
      value: 'bool',
      label: 'Логический'
    }
  ];

  const onSubmit = e => {
    e.preventDefault();

    axios
      .post('/api/v1/private/category-property/create', {
        categoryId: category ? parseInt(category.value) : 0,
        propertyId: property ? parseInt(property.value) : 0,
        groupName,
        type: type ? type.value : null,
        unit
      })
      .then(({ data = {} }) => {
        const { id = null } = data;
        setId(id);
        onSuccess(id);
      })
  };

  const resetForm = () => {
    setId(null);
    setCategory(null);
    setProperty(null);
    setGroupName('');
    setType(null);
    setUnit('');
  };

  const getForm = () => <form onSubmit={ onSubmit }>
    <div className="form-group">
      <label>Категория</label>
      <Select options={ categories }
              value={ category }
              onChange={ e => setCategory(e === null
                ? null
                : categories.find(el => el.value == e.value))
              }
              isDisabled={ isAwait }
      />
    </div>

    <div className="form-group">
      <label>Свойство</label>
      <Select options={ properties }
              value={ property }
              onChange={ e => setProperty(e === null
                ? null
                : properties.find(el => el.value == e.value))
              }
              isDisabled={ isAwait }
      />
    </div>

    <div className="form-group">
      <label>Тип</label>
      <Select options={ types }
              value={ type }
              onChange={ e => setType(e === null
                ? null
                : types.find(el => el.value == e.value))
              }
              isDisabled={ isAwait }
      />
    </div>

    <div className="form-group">
      <label>Группа</label>
      <input value={ groupName }
             className={ 'form-control' }
             onChange={ e => setGroupName(e.target.value) }
             disabled={ isAwait }
      />
    </div>

    <div className="form-group">
      <label>Единица измерения</label>
      <input value={ unit }
             className={ 'form-control' }
             onChange={ e => setUnit(e.target.value) }
             disabled={ isAwait }
      />
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

  return <>{ id ? getFinishScreen() : getForm() }</>;
};

export default CreateCategoryPropertyForm;
