import React from 'react';

const temp = () => {
    return (
        <div>
             <Row className="mt-4 mb-4 ms-1 ">
                <BannerComponent />
            </Row>

            <Row className="mt-4 mb-4 ms-1 pt-4 pb-3 rounded ">
                {/* <CardComponent /> */}
                <DeejayComponent />
            </Row>
            
            <Row className="mt-4 mb-4 ms-1  pt-4 pb-3  rounded text-light videocomponentbg">
                <Col  xs={12} md={8} className=" p-3 rounded">
                    <VideoComponent />
                </Col>

                <Col xs={12} md={4} className=" p-3 rounded ">
                    <ChatroomComponent />
                </Col>
            </Row>

            {/* <Row className="justify-content-center mt-4 mb-4 ms-1 pt-4 pb-3">
                <VoteComponent />
            </Row> */}

            <Row className="justify-content-center mt-4 mb-4 ms-1 pt-4 pb-3">
                 <SongComponent /> 
            </Row>
        </div>
    );
};

export default temp;