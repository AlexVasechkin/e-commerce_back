import React from 'react';
import ProductsTableRow from './components/products-table-row';
import DataTable from '../../../common/data-table';


const ProductsTable = ({
    products = () => []
}) => {

    const getHead = () => <>
        <td>ID</td>
        <td>Артикул</td>
        <td>Название</td>
        <td>Количество</td>
        <td>Действия</td>
    </>;

    const getBody = () => products.map(item => {
        const { id = '' } = item;
        return <ProductsTableRow key={ `ptr${ id }` } item={ item } />
    })

    return <DataTable getHead={ getHead } getBody={ getBody } />
};

export default ProductsTable;
