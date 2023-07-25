import React from 'react';


const ProductsTableRow = ({
    item = {
        id: '',
        code: '',
        name: '',
        count: 0
    }
}) => {

    const {
        id = '',
        code = '',
        name = '',
        count = null
    } = item;

    return <tr>
        <th scope="row">{ `${ id }` }</th>
        <td>{ `${ code }` }</td>
        <td>{ `${ name }` }</td>
        <td>{ `${ count ?? '' }` }</td>
        <td>
            <div className="button-list">
                <a href={ `/control-panel/product-card?id=${ id }` }
                   className="btn btn-success-rgba"
                   target="_blank"
                   ><i className="feather icon-edit-2"></i></a>
            </div>
        </td>
    </tr>;
};

export default ProductsTableRow;
