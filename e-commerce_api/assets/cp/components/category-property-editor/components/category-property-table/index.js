import React from 'react';
import DataTable from '../../../common/data-table';
import CategoryPropertyTableRow from './category-property-table-row';


const CategoryPropertyTable = ({
 dataSet = []
}) => {
  const getHead = () => <>
    <td>ID</td>
    <td>Категория</td>
    <td>Свойство</td>
    <td>Группа</td>
    <td>Тип</td>
    <td>Ед. изм</td>
    <td>Активно</td>
    <td>Действия</td>
  </>;

  const wrapRow = row => <CategoryPropertyTableRow key={ `cp${ row.id }` } row={ row } />;

  const getBody = () => dataSet.map(row => wrapRow(row));

  return <DataTable getHead={ getHead } getBody={ getBody } />
};

export default CategoryPropertyTable;
