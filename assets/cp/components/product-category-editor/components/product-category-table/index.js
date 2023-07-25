import React from 'react';
import DataTable from '../../../common/data-table';
import ProductCategoryTableRow from './product-category-table-row';


const ProductCategoryTable = ({
  dataSet = [],
  productCategoryDict = []
}) => {
  const getHead = () => <>
    <td>ID</td>
    <td>Родитель</td>
    <td>Категория</td>
    <td>В ед. числе</td>
    <td>Активно</td>
    <td>Страница</td>
    <td>Изображение</td>
    <td>Действия</td>
  </>;

  const wrapRow = row =>
    <ProductCategoryTableRow row={ row }
                             key={ `pc${row.id}` }
                             productCategoryDict={ productCategoryDict }
    />
  ;

  const getBody = () => dataSet.map(row => wrapRow(row));

  return <DataTable getHead={ getHead } getBody={ getBody } />
};

export default ProductCategoryTable;
