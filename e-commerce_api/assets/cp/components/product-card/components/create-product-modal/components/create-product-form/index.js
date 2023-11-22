import React, { useState } from 'react';
import Select from 'react-select';
import axios from 'axios';


const CreateProductForm = ({
    getVendors = () => [],
    onSuccess = id => null,
    productCategoryId = null
}) => {

    const [isAwait, setIsAwait] = useState(false);
    const [code, setCode] = useState('');
    const [vendorId, setVendorId] = useState(null);

    const [id, setId] = useState(null);


    const submit = e => {
        e.preventDefault();

        setIsAwait(true);

        axios
            .post('/api/v1/private/product/create',
            {
                code,
                vendorId,
                productCategoryId
            })
            .then(({ data = {} }) => {
                const { id = null } = data;
                setId(id);
                setCode('');
                setVendorId(null);
                onSuccess(id);
            })
            .finally(() => setIsAwait(false))
    };

    const resetForm = () => {
        setCode('');
        setVendorId(null);
        setId(null);
    };

    const getForm = () => <form onSubmit={ submit } >
        <div className="form-group">
            <label>Код товара</label>
            <input type="text"
                disabled={ isAwait }
                className="form-control"
                value={ code }
                onChange={ e => setCode(e.target.value) }
                />
        </div>

        <div className="form-group">
            <label>Производитель</label>

            <Select options={ getVendors() }
                value={ vendorId ? getVendors().find(k => k.value == vendorId) : '' }
                onChange={ v => setVendorId(parseInt(v.value)) }
                disabled={ isAwait }
            />
        </div>

        <div className="form-group">
            <button type="submit"
                    className="btn btn-info"
                    >Создать</button>
        </div>
    </form>;

    const openLink = () => {
        return <div>
            <div className="text-center">Товар успешно создан</div>
            <div className="text-center">

                <button className="btn btn-outline-light" onClick={ resetForm }>Добавить еще</button>

                <a target='_blank'
                   href={ `/control-panel/product-card?id=${ id }` }
                   className='btn btn-outline-info' >Перейти к редактированию</a>

            </div>
        </div>;
    };

    return <>{ id ? openLink() : getForm() }</>;
};

export default CreateProductForm;
