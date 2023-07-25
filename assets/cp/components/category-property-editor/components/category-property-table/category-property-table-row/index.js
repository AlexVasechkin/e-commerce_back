import React, {useState} from 'react';
import axios from 'axios';
import categoryPropertyEditorStateInstance from '../../../state';


const CategoryPropertyTableRow = ({
  row = {
    id: '',
    categoryName: '',
    propertyName: '',
    groupName: '',
    type: '',
    unit: '',
    isActive: null
  }
}) => {

  const { id = '' } = row;

  const [isAwait, setIsAwait] = useState(false);
  const [groupName, setGroupName] = useState(row.groupName ?? '');
  const [unit, setUnit] = useState(row.unit ?? '');
  const [isActive, setIsActive] = useState(row.isActive ?? null);
  const [type, setType] = useState(row.type ?? '');

  const getIsActiveValue = () => {
    if (isActive === null) {
      return '';
    } else if (isActive) {
      return '1';
    } else {
      return '0';
    }
  };

  const save = () => {
    setIsAwait(true);

    axios
      .post('/api/v1/private/category-property/update', {
        id,
        groupName,
        unit,
        isActive,
        type
      })
      .catch(() => {
        setGroupName(row.groupName ?? '');
        setUnit(row.unit ?? '');
        setIsActive(row.isActive ?? null);
        setType(row.type ?? '')
      })
      .finally(() => setIsAwait(false))
  };

  const remove = () => {
    setIsAwait(true);

    axios
      .post('/api/v1/private/category-property/remove', {id})
      .then(() => {
        categoryPropertyEditorStateInstance.removeDsItem(id);
      })
      .finally(() => setIsAwait(false))
  };

  return <tr>
    <td>{ `${ row.id }` }</td>
    <td>{ `${ row.categoryName }` }</td>
    <td>{ `${ row.propertyName }` }</td>
    <td>
      <input disabled={ isAwait }
             className="form-control"
             value={ `${ groupName }` }
             onChange={ e => setGroupName(e.target.value) }
      />
    </td>
    <td>
      <select value={ type }
              onChange={ e => setType(e.target.value) }
              className={ 'form-control' }
              disabled={ isAwait }
      >
        <option value="string">Строковый</option>
        <option value="int">Целое число</option>
        <option value="float">Дробное число</option>
        <option value="bool">Логический</option>
      </select>
    </td>
    <td>
      <input disabled={ isAwait }
             className="form-control"
             value={ `${ unit }` }
             onChange={ e => setUnit(e.target.value) }
      />
    </td>
    <td>
      <select onChange={ e => { if (e.target.value === '') {setIsActive(null)} else if (e.target.value === '1') {setIsActive(true)} else {setIsActive(false)} } }
              value={ getIsActiveValue() }
              className={ 'form-control' }
              disabled={ isAwait }
      >
        <option value="">{ 'Не указано' }</option>
        <option value="1">{ 'Да' }</option>
        <option value="0">{ 'Нет' }</option>
      </select>
    </td>
    <td>
      <div className="btn-group">
        <button disabled={ isAwait } className="btn btn-outline-success" onClick={ () => save() }><i className="fa fa-save" /></button>
        <button disabled={ isAwait } className="btn btn-outline-light" onClick={ () => remove() }><i className="fa fa-trash" /></button>
      </div>
    </td>
  </tr>;
};

export default CategoryPropertyTableRow;
