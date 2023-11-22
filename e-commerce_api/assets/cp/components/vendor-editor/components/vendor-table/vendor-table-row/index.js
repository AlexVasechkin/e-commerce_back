import React, {useState} from 'react';
import vendorEditorStateInstance from '../../../state';
import axios from 'axios';


const VendorTableRow = ({
  row = {
    id: '',
    name: ''
  }
}) => {

  const { id = '' } = row;

  const [name, setName] = useState(row.name);
  const [isAwait, setIsAwait] = useState(false);

  const save = () => {
    setIsAwait(true);

    axios
      .post('/api/v1/private/vendor/update', {
        id,
        name
      })
      .catch(() => {
        setName(row.name);
      })
      .finally(() => setIsAwait(false))
  };

  const remove = () => {
    setIsAwait(true);

    axios
      .post('/api/v1/private/vendor/remove', {id})
      .then(() => {
        vendorEditorStateInstance.removeDsItem(id);
      })
      .finally(() => setIsAwait(false))
  };

  return <tr>
    <td>{ `${ id }` }</td>
    <td><input disabled={ isAwait } className="form-control" value={ `${ name }` } onChange={ e => setName(e.target.value) }/></td>
    <td><div className="btn-group">
      <button disabled={ isAwait } className="btn btn-outline-success" onClick={ () => save() }><i className="fa fa-save" /></button>
      <button disabled={ isAwait } className="btn btn-outline-light" onClick={ () => remove() }><i className="fa fa-trash" /></button>
    </div></td>
  </tr>
};

export default VendorTableRow;
