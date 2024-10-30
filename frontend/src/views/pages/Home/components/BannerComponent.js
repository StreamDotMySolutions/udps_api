import  { useEffect, useState } from 'react'
import { Carousel, FigureImage, Image,Row } from 'react-bootstrap'
import axios from 'axios'
import { Link } from 'react-router-dom';

const BannerComponent = () => {

  const url = process.env.REACT_APP_API_URL;
  const serverUrl = process.env.REACT_APP_SERVER_URL;
  const [banners,setBanners] = useState([]);
  const [isLoading,setIsLoading] = useState(false);

  useEffect( () => {
    setIsLoading(true)
    axios(`${url}/homepage/banners`)
    .then( response => {
      //console.log(response)
      setBanners(response.data.banners)
    })
    .catch( error => {
      console.warn(error)
    })
    .finally( () =>{ 
      setIsLoading(false)
    })
  },[])

  const carouselItems = () => {

    if (!banners || banners.length === 0) {
      // Return some default content or a message indicating no banners available
      return <div></div>;
    } 

    if (banners.length > 0) {
      return banners.map((banner, index) => {

          return (
          
    
              <Carousel.Item key={index}>{
                banner?.filename && 
                <Link to={banner.redirect_url}>
                  <FigureImage
                    className="d-block w-100 img-fluid"
                    //style={{ 'height' : '600px'}}
                    src={`${serverUrl}/storage/banners/${banner.filename}`} 
                    alt="First slide"
                  />
                </Link> 
                }
              </Carousel.Item>
          
           
          );
      });
      
  }


  }

  if (banners.length > 0){
    return (
      <Row className="mt-4 mb-4 ms-1 ">
        <Carousel>
           {carouselItems()}
        </Carousel>
        </Row>
      );
  }
 
}

export default BannerComponent;
