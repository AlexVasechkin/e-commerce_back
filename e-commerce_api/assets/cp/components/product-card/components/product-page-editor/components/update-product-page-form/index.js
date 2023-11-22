import React from 'react';
import productPageEditorStateInstance from '../../state';
import {observer} from 'mobx-react-lite';

const ProductPageEditorUpdateProductPageForm = observer(() => {

  const onSubmit = e => {
    e.preventDefault();

    productPageEditorStateInstance.update();
  };


  return <form onSubmit={ onSubmit }>
    <div className="form-group" >
      <label>Внутреннее название</label>
      <input type="text"
             className="form-control"
             value={ `${ productPageEditorStateInstance.dataSet.name ?? '' }` }
             onChange={ e => productPageEditorStateInstance.dataSet.name = e.target.value } />
    </div>

    <div className="form-group" >
      <label>Титульный заголовок</label>
      <input type="text"
             className="form-control"
             value={ `${ productPageEditorStateInstance.dataSet.pagetitle ?? '' }` }
             onChange={ e => productPageEditorStateInstance.dataSet.pagetitle = e.target.value } />
    </div>

    <div className="form-group" >
      <label>Заголовок</label>
      <input type="text"
             className="form-control"
             value={ `${ productPageEditorStateInstance.dataSet.headline ?? '' }` }
             onChange={ e => productPageEditorStateInstance.dataSet.headline = e.target.value } />
    </div>

    <div className="form-group" >
      <label>Адрес</label>
      <input type="text"
             className="form-control"
             value={ `${ productPageEditorStateInstance.dataSet.alias ?? '' }` }
             onChange={ e => productPageEditorStateInstance.dataSet.alias = e.target.value } />
    </div>

    <div className="form-group">
      <label>
        <input type="checkbox"
               checked={ productPageEditorStateInstance.dataSet.isActive }
               onChange={() => null}
               onClick={ () => productPageEditorStateInstance.dataSet.isActive = !productPageEditorStateInstance.dataSet.isActive } /> Активно</label>
    </div>

    <div className="form-group">
      <button type="submit" className="btn btn-outline-info">Сохранить</button>
    </div>

  </form>;
});

export default ProductPageEditorUpdateProductPageForm;
