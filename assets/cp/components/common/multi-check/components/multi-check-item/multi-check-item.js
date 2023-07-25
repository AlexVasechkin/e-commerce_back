import React from 'react'


const MultiCheckItem = ({
  onClick,
  label,
  checked
}) => {

  return (
    <label>
      <input type="checkbox"
             checked={ checked }
             onChange={ () => null }
             onClick={ onClick }
      />{ ` ${ label }` }</label>
  )
};

export default MultiCheckItem;
