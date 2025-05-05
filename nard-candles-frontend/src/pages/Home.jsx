import { useState, useEffect } from 'react';
import './Home.css';
import mostSold1 from '../assets/mostSold1.jpg';
import mostSold2 from '../assets/mostSold2.jpg';
import mostSold3 from '../assets/mostSold3.jpg';
import mostSold4 from '../assets/mostSold4.jpg';
import mostSold5 from '../assets/mostSold5.jpg';
import mostSold6 from '../assets/mostSold6.jpg';
import mostLiked1 from '../assets/mostLiked1.jpg';
import mostLiked2 from '../assets/mostLiked2.jpg';
import mostLiked3 from '../assets/mostLiked3.jpg';
import { Link } from "react-router-dom";

const Home = () => {
  const [promotions, setPromotions] = useState([]);
  const [currentPromotion, setCurrentPromotion] = useState(0);
  const [featuredCandles, setFeaturedCandles] = useState([]);
  const [videos, setVideos] = useState([]);
  const baseURL = 'http://localhost:8000/';

  useEffect(() => {
    fetch(`${baseURL}api/promotions`)
      .then((response) => response.json())
      .then((data) => setPromotions(data))
      .catch((error) => console.error('Error fetching promotions:', error));
  }, [baseURL]);

  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentPromotion((prevIndex) => (prevIndex + 1) % promotions.length);
    }, 7000);
    return () => clearInterval(interval);
  }, [promotions]);

  console.log(promotions);

  useEffect(() => {
    const fetchFeaturedProducts = async () => {
      try {
        console.log('Fetching featured products from:', `${baseURL}api/featured_products`);
        const response = await fetch(`${baseURL}api/featured_products`);
        
        if (!response.ok) {
          throw new Error(`Failed to fetch featured products: ${response.status} ${response.statusText}`);
        }
        
        const data = await response.json();
        console.log('Featured products data:', data);
        setFeaturedCandles(data);
      } catch (error) {
        console.error('Error fetching featured products:', error);
      }
    };
    
    fetchFeaturedProducts();
  }, [baseURL]);

  const currentPromo = promotions[currentPromotion];

  useEffect(() => {
    const fetchYouTubeVideos = async () => {
      try {
        console.log('Fetching YouTube videos from:', `${baseURL}api/youtube-videos`);
        const response = await fetch(`${baseURL}api/youtube-videos`);
        
        if (!response.ok) {
          throw new Error(`Failed to fetch videos: ${response.status} ${response.statusText}`);
        }
        
        const data = await response.json();
        console.log('YouTube videos data:', data);
        setVideos(data);
      } catch (error) {
        console.error('Error fetching videos:', error);
      }
    };
    
    fetchYouTubeVideos();
  }, [baseURL]);

  // Helper function to extract YouTube video ID from different URL formats
  const extractVideoId = (url) => {
    if (!url) return null;
    
    // Handle if the URL is already just the ID
    if (url.length === 11 && /^[a-zA-Z0-9_-]{11}$/.test(url)) {
      return url;
    }
    
    let regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
    let match = url.match(regExp);
    
    if (match && match[7].length === 11) {
      return match[7];
    } else {
      console.warn('Could not extract video ID from URL:', url);
      return null;
    }
  };

  const styles = {
    video: {
        padding: '20px',
    },
    videoGallery: {
        display: 'flex',
        flexWrap: 'wrap',
        gap: '20px',
    },
    videoItem: {
        flex: '1 1 calc(30% - 20px)', // Responsive item width
    },
  };

  return (
    <div className="home-container">
      {/* Hero Section */}
      <section
        className="promotion-background"
        style={{
          backgroundImage: currentPromo
            ? `url(${baseURL}storage/${currentPromo.media})`
            : 'none',
        }}
      >
        {currentPromo && (
          <div className="hero">
            <div className="hero-content">
              <h1>Welcome to Nard Candles</h1>
              <p>Bringing warmth and light into your home with our handcrafted, all-natural candles.</p>
              <button className="cta-button">Shop Now</button>
            </div>
          </div>
        )}
      </section>

      {/* Featured Products */}
      <section className="gallery">
        <h2>Our Featured Candles</h2>
        <div className="gallery-scroll">
          {featuredCandles.map((candle) => (
            <div key={candle.id} className="gallery-item">
              <img 
                src={`${baseURL}storage/${candle.image}`} 
                alt={candle.name} 
                onError={(e) => {
                  console.log('Image failed to load:', `${baseURL}storage/${candle.image}`);
                  e.target.onerror = null;
                  e.target.src = 'https://via.placeholder.com/150?text=Product';
                }}
              />
              <p>{candle.name}</p>
            </div>
          ))}
        </div>
      </section>

      {/* Video Section */}
      <section style={styles.video}>
        <h2>Discover Nard Candles</h2>
        {videos.length > 0 ? (
          <div style={styles.videoGallery}>
            {videos.map((video) => {
              let videoId;
              try {
                videoId = extractVideoId(video.link);
              } catch (error) {
                console.error('Error extracting video ID:', error, video);
                videoId = null;
              }
              
              return (
                <div key={video.id} style={styles.videoItem}>
                  {videoId ? (
                    <iframe
                      src={`https://www.youtube.com/embed/${videoId}`}
                      frameBorder="0"
                      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                      allowFullScreen
                      width="100%"
                      height="250"
                      onError={(e) => {
                        console.error('Iframe error:', e);
                      }}
                    ></iframe>
                  ) : (
                    <div style={{ 
                      backgroundColor: '#f0f0f0', 
                      height: '250px', 
                      display: 'flex', 
                      alignItems: 'center', 
                      justifyContent: 'center' 
                    }}>
                      <p>Video Unavailable</p>
                    </div>
                  )}
                  <p>{video.description}</p>
                </div>
              );
            })}
          </div>
        ) : (
          <div className="no-videos-message">
            <p>No videos available at the moment. Please check back later.</p>
          </div>
        )}
      </section>

      {/* Most Sold of the Week */}
      <section className="most-sold">
        <h2>Most Sold of the Week</h2>
        <div className="most-sold-gallery">
          {[mostSold1, mostSold2, mostSold3, mostSold4, mostSold5, mostSold6].map((image, index) => (
            <div key={index} className="most-sold-item">
              <img src={image} alt={`Most Sold ${index + 1}`} />
              <p>Product {index + 1} - {Math.floor(Math.random() * 150)} Orders</p>
            </div>
          ))}
        </div>
      </section>

      {/* Most Liked Candle Product Section */}
      <section className="most-liked">
        <h2>Most Liked Candle Product</h2>
        <div className="most-liked-gallery">
          {[mostLiked1, mostLiked2, mostLiked3].map((image, index) => (
            <div key={index} className="most-liked-item">
              <img src={image} alt={`Most Liked ${index + 1}`} />
              <p>Product {index + 1} - <span className="likes"><i className="fa fa-heart"></i> {Math.floor(Math.random() * 200)} Likes</span></p>
            </div>
          ))}
        </div>
      </section>

      {/* Mission Statement Section */}
      <section className="mission">
        <h2>Our Mission</h2>
        <p>
          At Nard Candles, we believe in creating a calming and serene environment in every home.
          Our mission is to provide high-quality, eco-friendly candles that elevate your space
          and enhance your well-being.
        </p>
      </section>

      {/* Company History Section */}
      <section className="history">
        <h2>Our Story</h2>
        <p>
          Founded in 2020, Nard Candles began as a small passion project in a home kitchen.
          Today, we have grown into a beloved brand known for our dedication to quality,
          sustainability, and customer satisfaction.
        </p>
      </section>

      {/* Unique Selling Points Section */}
      <section className="usp">
        <h2>Why Choose Us?</h2>
        <ul>
          <li>100% Natural Ingredients</li>
          <li>Handcrafted with Love</li>
          <li>Eco-friendly Packaging</li>
          <li>Wide Range of Scents</li>
        </ul>
      </section>

      
  <Link to="/register">
    <button className="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
      Register for Training
    </button>
  </Link>

    </div>
  );
};

export default Home;
