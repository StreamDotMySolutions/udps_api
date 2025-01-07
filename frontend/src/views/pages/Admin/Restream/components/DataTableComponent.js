
import React, {  } from 'react';
import { Badge, Card, Figure, Table } from 'react-bootstrap';
import useStore from '../../../../../store';
import Ordering from './OrderingComponent';
import PaginatorLink from '../../../../../libs/PaginatorLink';
import CreateButton from '../../../../../libs/CreateButton';
import CreateModal from '../modals/CreateModal';
import EditModal from '../modals/EditModal';
import DeleteModal from '../modals/DeleteModal';
import StreamComponent from './StreamComponent';

const DataTableComponent = () => {
    const store = useStore()
    const items = store.getValue('restreams'); 
    const url = process.env.REACT_APP_SERVER_URL; 
    //console.log(items)
    return (
        <div>
            <CreateButton>
                <CreateModal />
            </CreateButton>
            
   
            <Table>
                <thead>
                    <tr>
                        <th style={{ 'width': '20px'}}>ID</th>
                        <th style={{ 'width': '20px'}} className='text-center'>Ordering</th>
                        <th  style={{ 'width': '100vH'}}>Restream Destination</th>
                        <th style={{ 'width': '20px'}} className='text-center'>PID</th>
                        <th style={{ 'width': '20px'}} className='text-center'>Status</th>
                        <th style={{ 'width': '20px'}} className='text-center'>Stream</th>
                        <th className='text-center'>Action</th>
                    </tr>
                </thead>

                <tbody>
                    {items ? 
                    (
                        <>
                            {items.data && items.data.length > 0 ? 
                            (
                                <>
                                    {items.data.map((item, index) => (
                                        <tr key={index}>
                                            <td><span className="badge bg-dark">{item.id}</span></td>    
                                            <td className='text-center' style={{width: '100px'}}>
                                                <Ordering id={item.id} direction='up' disabled={index === 0}/>
                                                {' '}
                                                <Ordering id={item.id} direction='down' disabled={index === items.data.length - 1}/>
                                            </td>
                                
                                        
                              
                                            <td style={{width: '*'}}>
                                               
                                                <Card className='p-3'>

                                                    {/* <Figure>
                                                        <Figure.Image
                                                            width={'300px'}
                                                            className="img-fluid"
                                                            src={`${url}/storage/restreams/${item.filename}`}
                                                            alt="Preview"
                                                        />
                                                    </Figure> */}
                                                    <h3>{item.name}</h3>
                                            
                                                    <div dangerouslySetInnerHTML={{ __html: item.rtmp_address}} />
                                                </Card>
                                              
                                                
                                            </td>
                                            <td className='text-center'>{item.pid}</td> 
                                            <td className='text-center'>{item.is_active}</td>    
                                            <td className='text-center'><StreamComponent isActive={item.is_active} id={item.id} /></td>   
                                                    
                                                        
                                            <td className='text-center' style={{width: '200px'}}>
                                          
                                                {' '}
                                                <EditModal id={item.id} />
                                                {' '}
                                                <DeleteModal id={item.id} />
                                            </td>
                                        </tr>
                                    ))}
                                </>
                            ) : 
                            (
                                <tr>
                                    <td colSpan={7} className="text-center">No data available</td>
                                </tr>
                            )}
                        </>
                    ) : 
                    (
                        <tr>
                            <td colSpan={7} className="text-center">No data available</td>
                        </tr>
                    )}
                </tbody>

            </Table>
            <PaginatorLink store={store} items={items} />
        </div>
    );
};

export default DataTableComponent;