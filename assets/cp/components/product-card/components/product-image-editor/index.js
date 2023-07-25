import React, {useState} from 'react';
import {observer} from 'mobx-react-lite';
import productImageEditorStateInstance from './state';


const ProductImageEditor = observer(() => {

  const [filterMode, setFilterMode] = useState(0);

  const images = productImageEditorStateInstance.images;

  const findItem = id => {
    const idx = images.findIndex(el => el.id === id);
    return idx > -1 ? images[idx] : null;
  };

  const updateDeleted = (id, isDeleted) => {
    productImageEditorStateInstance.update({
      id,
      isDeleted
    });
  };

  const wrapItem = item => {

    const {
      id = '',
      description = '',
      sortOrder = 0,
      path = '',
      isDeleted = false
    } = item;

    const onSubmit = e => {
      e.preventDefault();
      const item = findItem(id);
      productImageEditorStateInstance.update({
        ...{
          id: item.id,
          description: item.description,
          sortOrder: item.sortOrder
        }
      });
    };

    return <div key={`piei_${ id }`} style={{ float: 'left', marginRight: '15px'}}>
      <div style={{width: '290px'}}>
        <div style={{
            display: 'flex',
            width: '100%',
            height: '26vh',
            alignItems: 'center',
            justifyContent: 'center',
            position: 'relative',
            border: '1px solid #d3d3d3',
            borderRadius: '4px'
        }}><img style={{maxWidth: '90%', maxHeight: '90%'}} src={ `${ path }` } alt={''} /></div>

        <div className="card-body">
          <form onSubmit={onSubmit}>
            <div className="form-group">
              <textarea className="form-control" onChange={ e => findItem(id).description = e.target.value } rows="3" placeholder="Описание" value={ description }/>
            </div>

            <div className="form-group">
              <input type="number" onChange={ e => findItem(id).sortOrder = parseInt(e.target.value) } min={0} className="form-control" value={ sortOrder }/>
            </div>

            <div className="form-group text-right">
              <div className="btn-group">
                <button type="submit" className="btn btn-outline-info"><i className="feather icon-save mr-2" />Сохранить</button>
              </div>
            </div>

          </form>

          <div>
            <button className="btn btn-outline-light"
                    onClick={ () => updateDeleted(id, !isDeleted) }><i className="fa fa-trash-o" />&nbsp;{ isDeleted ? 'Восстановить' : 'Удалить' }</button>
          </div>

        </div>
      </div>
    </div>;
  };

  const sortImages = images => {
    if (filterMode === 0) {
      return images
        .filter(el => el.isDeleted === false)
        .sort((a, b) => {
          if (a.sortOrder === b.sortOrder) {
            return 0;
          }
          return a.sortOrder > b.sortOrder ? 1 : -1;
        });
    }

    if (filterMode === 1) {
      return images.filter(el => el.isDeleted === true);
    }

    return [
      ...images
        .filter(el => el.isDeleted === false)
        .sort((a, b) => {
          if (a.sortOrder === b.sortOrder) {
            return 0;
          }
          return a.sortOrder > b.sortOrder ? 1 : -1;
      }),
      ...images.filter(el => el.isDeleted === true)
    ]
  };

  return <>
    <div className="btn-group m-b-15">
      <button onClick={ () => setFilterMode(0) } className={ `btn btn-light ${ filterMode === 0 ? 'active' : '' }` } >Активные</button>
      <button onClick={ () => setFilterMode(1) } className={ `btn btn-light ${ filterMode === 1 ? 'active' : '' }` } >Удаленные</button>
      <button onClick={ () => setFilterMode(2) } className={ `btn btn-light ${ filterMode === 2 ? 'active' : '' }` } >Все</button>
    </div>

    <div>
      { sortImages(images).map(item => wrapItem(item)) }
    </div>

  </>;

});

export default ProductImageEditor;
