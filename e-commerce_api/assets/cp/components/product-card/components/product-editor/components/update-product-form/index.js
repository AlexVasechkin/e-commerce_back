import React from "react";
import { observer } from 'mobx-react-lite';
import updateProductFormStateInstance from './state';
import Select from 'react-select';
import productCardStateInstance from '../../../../state';


const UpdateProductForm = observer(() => {

    const onSubmit = e => {
        e.preventDefault();
        updateProductFormStateInstance.update();
    };

    return <form onSubmit={ onSubmit }>
        <div className="form-group">
            <label>Код товара</label>
            <input type="text"
                   disabled={updateProductFormStateInstance.isAwait}
                   className="form-control"
                   value={ updateProductFormStateInstance.formData.code }
                   onChange={ e => updateProductFormStateInstance.formData.code = e.target.value }
                   />
        </div>

        <div className="form-group">
            <label>Производитель</label>

            <Select options={updateProductFormStateInstance.getVendors()}
                value={ updateProductFormStateInstance.formData.vendorId ? updateProductFormStateInstance.getVendors().find(k => k.value == updateProductFormStateInstance.formData.vendorId) : '' }
                onChange={ v => updateProductFormStateInstance.formData.vendorId = parseInt(v.value) }
                disabled={updateProductFormStateInstance.isAwait}
            />
        </div>

        <div className="form-group">
            <label>Название</label>
            <input type="text"
                   disabled={updateProductFormStateInstance.isAwait}
                   className="form-control"
                   value={ updateProductFormStateInstance.formData.name }
                   onChange={ e => updateProductFormStateInstance.formData.name = e.target.value }
                   />
        </div>

        <hr/>

        <div className="form-group">
            <label>Цена</label>
            <input type="number"
                   disabled={updateProductFormStateInstance.isAwait}
                   className="form-control"
                   value={ updateProductFormStateInstance.formData.price }
                   onChange={ e => updateProductFormStateInstance.formData.price = parseInt(e.target.value) }
                   />
        </div>

        <div className="form-group">
            <label>Страница донор</label>
            <input type="url"
                   disabled={updateProductFormStateInstance.isAwait}
                   className="form-control"
                   value={ updateProductFormStateInstance.formData.donorUrl ?? '' }
                   onChange={ e => updateProductFormStateInstance.formData.donorUrl = `${ e.target.value }` }
            />
        </div>

        <div className="form-group">
            <label>Код парсера</label>
            <select className="form-control"
                    value={ updateProductFormStateInstance.formData.parserCode ?? '' }
                    onChange={ e => updateProductFormStateInstance.formData.parserCode = (e.target.value === '' ? null : e.target.value) }
            >
                <option value={ '' }>{ 'Не указано' }</option>
                {
                    productCardStateInstance.parsers.map(item => {
                        return (
                          <option value={ `${ item.code }` }>{ `${ item.name }` }</option>
                        )
                    })
                }
            </select>
        </div>

        <hr/>

        <div className="form-group">
            <label>Количество</label>
            <input type="number"
                   disabled={updateProductFormStateInstance.isAwait}
                   className="form-control"
                   value={ updateProductFormStateInstance.formData.count }
                   onChange={ e => updateProductFormStateInstance.formData.count = parseInt(e.target.value) }
                   />
        </div>

        <div className="form-group">
            <label>Длинна, мм</label>
            <input type="number"
                   disabled={updateProductFormStateInstance.isAwait}
                   className="form-control"
                   value={ updateProductFormStateInstance.formData.length }
                   onChange={ e => updateProductFormStateInstance.formData.length = parseInt(e.target.value) }
                   />
        </div>

        <div className="form-group">
            <label>Ширина, мм</label>
            <input type="number"
                   disabled={updateProductFormStateInstance.isAwait}
                   className="form-control"
                   value={ updateProductFormStateInstance.formData.width }
                   onChange={ e => updateProductFormStateInstance.formData.width = parseInt(e.target.value) }
                   />
        </div>

        <div className="form-group">
            <label>Высота, мм</label>
            <input type="number"
                   disabled={updateProductFormStateInstance.isAwait}
                   className="form-control"
                   value={ updateProductFormStateInstance.formData.height }
                   onChange={ e => updateProductFormStateInstance.formData.height = parseInt(e.target.value) }
                   />
        </div>

        <div className="form-group">
            <label>Вес, мм</label>
            <input type="number"
                   disabled={updateProductFormStateInstance.isAwait}
                   className="form-control"
                   value={ updateProductFormStateInstance.formData.mass }
                   onChange={ e => updateProductFormStateInstance.formData.mass = parseInt(e.target.value) }
                   />
        </div>

        <div className="form-group">
            <button disabled={ updateProductFormStateInstance.isAwait }
                    type="submit"
                    className="btn btn-default"
                    >{ updateProductFormStateInstance.isAwait
                       ? 'Ожидание'
                       : 'Сохранить'
                    }</button>
        </div>
    </form>;
});

export default UpdateProductForm;
