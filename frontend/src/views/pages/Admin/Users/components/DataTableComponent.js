
import React, { useState, useEffect } from 'react';
import { Table,Button, Badge } from 'react-bootstrap';
import useStore from '../../../../../store';
import PaginatorLink from '../../../../../libs/PaginatorLink';
import CreateButton from '../../../../../libs/CreateButton';
import CreateModal from '../modals/CreateModal';
import EditModal from '../modals/EditModal';
import DeleteModal from '../modals/DeleteModal';

const DataTableComponent = () => {
    const store = useStore()
    const items = store.getValue('users')
    return (
        <div>
            <CreateButton>
                <CreateModal />
            </CreateButton>
            
   
            <Table>
                <thead>
                    <tr>
                        <th style={{ 'width': '20px'}}>ID</th>
                        <th>Status</th>
                        <th>Role</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th className='text-center'></th>
                    </tr>
                </thead>

                <tbody>
                    {items ?
                        <>
                            {items?.data?.map((item,index) => (
                            <tr key={index}>
                                <td><span className="badge bg-dark">{item.id}</span></td>
                                <td style={{ width: '80px' }}>
                                <td style={{ width: '80px' }}>
                                    <Badge
                                        bg={item.status === 1 ? 'success' : 'secondary'} // success = green, secondary = grey
                                        className="p-2 text-white"
                                    >
                                        {item.status === 1 ? 'Active' : 'Inactive'}
                                    </Badge>
                                </td>

                                </td>

                                <td style={{'width':'80px'}}>
                                    {item.roles?.map((role, index) => (
                                        <React.Fragment key={index}>
                                        {' '}<Badge bg='warning' className='p-2 text-dark'>{role.name}</Badge>
                                        </React.Fragment>
                                    ))}
                                </td>
                                <td>{item.name}</td>
                                <td style={{'width':'150px'}}>{item.email}</td>
                                <td style={{'width':'180px'}}>{item.created_at}</td>
                                <td className='text-center' style={{'width':'200px'}}>
                                    <EditModal id={item.id} />
                                    {' '}
                                    <DeleteModal id={item.id} />
                                </td>
                            </tr> 
                        ))}
                        </>
                    :
                        <tr>
                            <td colSpan={6}>loading ...</td>
                        </tr>
                    }
                   
                </tbody>
            </Table>
            <PaginatorLink store={store} items={items} />
        </div>
    );
};

export default DataTableComponent;