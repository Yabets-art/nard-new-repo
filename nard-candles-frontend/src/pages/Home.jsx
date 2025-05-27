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
import { Link, useNavigate } from "react-router-dom";
import placeholderImage from '../assets/placeholder.svg';
import axios from 'axios';

const Home = () => {
  const navigate = useNavigate();
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
        const response = await axios.get(`${baseURL}api/products`);
        
        if (Array.isArray(response.data) && response.data.length > 0) {
          // Get first 4 products as featured items
          const processedProducts = response.data.slice(0, 4).map(product => {
            let imagePath = product.image;
            if (imagePath === 'images/mostSold1.jpg' || imagePath === 'images/mostliked3.jpg') {
              imagePath = imagePath + '.jpg';
            }
            return {
              ...product,
              image: imagePath.startsWith('images/') ? imagePath : `images/${imagePath}`
            };
          });
          setFeaturedCandles(processedProducts);
        }
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

  // Add this function at the top level of the component
  const handleImageError = (e, imagePath) => {
    console.log('Image failed to load:', imagePath);
    e.target.onerror = null; // Prevent infinite loop
    e.target.src = placeholderImage;
  };

  const handleProductClick = () => {
    navigate('/products');
  };

  useEffect(() => {
    // Enhanced scroll animation observer with both directions
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        // Remove animate class when element is not in view
        if (!entry.isIntersecting) {
          entry.target.classList.remove('animate');
          // Remove animation from children
          const children = entry.target.querySelectorAll('.stagger-item');
          children.forEach(child => {
            child.classList.remove('animate');
          });
        } else {
          // Add animation when element comes into view
          entry.target.classList.add('animate');
          // Add stagger effect for child elements
          const children = entry.target.querySelectorAll('.stagger-item');
          children.forEach((child, index) => {
            setTimeout(() => {
              child.classList.add('animate');
            }, index * 200);
          });
        }
      });
    }, {
      threshold: [0.1, 0.3], // Multiple thresholds for smoother triggering
      rootMargin: '0px 0px -100px 0px' // Slightly offset the trigger point
    });

    // Observe all animated elements
    document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right, .split-reveal, .slide-in, .gallery').forEach((element) => {
      observer.observe(element);
    });

    return () => observer.disconnect();
  }, []);

  return (
    <div className="home-container">
      {/* Hero Section - Full Screen */}
      <section
        className="promotion-background split-reveal"
        style={{
          backgroundImage: currentPromo
            ? `url(${baseURL}storage/${currentPromo.media})`
            : 'none',
        }}
      >
        <div className="hero">
          <div className="hero-content stagger-item">
            <h1>Welcome to Nard Candles</h1>
            <p>Bringing warmth and light into your home with our handcrafted, all-natural candles.</p>
            <button className="cta-button">Shop Now</button>
          </div>
        </div>
        <div className="scroll-indicator stagger-item">
          <div className="mouse"></div>
          <p>Scroll to explore</p>
        </div>
      </section>

      {/* Featured Products with Cloud Transition */}
      <section className="gallery">
        <div className="gallery-content">
          <h2 className="stagger-item">Our Featured Candles</h2>
          <div className="featured-products-container">
            <div className="gallery-scroll">
              {featuredCandles.map((candle) => (
                <div 
                  key={candle.id} 
                  className="gallery-item stagger-item"
                  onClick={handleProductClick}
                >
                  <div className="product-image-container">
                    <img 
                      src={`${baseURL}${candle.image}`} 
                      alt={candle.name}
                      onError={(e) => {
                        e.target.onerror = null;
                        e.target.src = placeholderImage;
                      }}
                    />
                    <div className="product-overlay">
                      <span className="view-details">View Details</span>
                    </div>
                  </div>
                  <div className="product-details">
                    <h3>{candle.name}</h3>
                    <p className="product-price">${parseFloat(candle.price).toFixed(2)}</p>
                  </div>
                </div>
              ))}
            </div>
            <div className="view-all-products">
              <Link to="/products" className="view-all-button">
                View All Products
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* Video Section */}
      <section className="discover-section split-reveal">
        <div className="section-container">
          <div className="discover-header stagger-item">
            <h2>Discover Nard Candles</h2>
            <p>Experience the art of candle making through our curated collection of videos</p>
          </div>
          
          {videos.length > 0 ? (
            <div className="discover-grid">
              {videos.map((video, index) => {
                let videoId;
                try {
                  videoId = extractVideoId(video.link);
                } catch (error) {
                  console.error('Error extracting video ID:', error, video);
                  videoId = null;
                }
                
                return (
                  <div 
                    key={video.id} 
                    className={`discover-item stagger-item ${index % 2 === 0 ? 'fade-in-left' : 'fade-in-right'}`}
                  >
                    <div className="video-wrapper">
                      <div className="video-container">
                        {videoId ? (
                          <iframe
                            src={`https://www.youtube.com/embed/${videoId}`}
                            title={video.title || 'Nard Candles Video'}
                            frameBorder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowFullScreen
                            loading="lazy"
                          ></iframe>
                        ) : (
                          <div className="video-placeholder">
                            <p>Video Unavailable</p>
                          </div>
                        )}
                      </div>
                    </div>
                    <div className="video-details">
                      <h3>{video.title || 'Nard Candles Creation'}</h3>
                      <p>{video.description}</p>
                    </div>
                  </div>
                );
              })}
            </div>
          ) : (
            <div className="no-videos-message stagger-item">
              <div className="message-content">
                <i className="video-icon">🎥</i>
                <p>No videos available at the moment. Please check back later.</p>
              </div>
            </div>
          )}
        </div>
      </section>

      {/* Custom Order Section */}
      <section className="custom-order-section split-reveal">
        <div className="section-container">
          <div className="custom-order-content">
            <div className="custom-order-text stagger-item">
              <h2>Create Your Perfect Candle</h2>
              <p>Want something unique? Design your own custom candle with our personalized service.</p>
              <ul className="custom-features">
                <li className="stagger-item">
                  <span className="feature-icon">🎨</span>
                  Choose Your Colors
                </li>
                <li className="stagger-item">
                  <span className="feature-icon">🌺</span>
                  Select Your Scent
                </li>
                <li className="stagger-item">
                  <span className="feature-icon">🎁</span>
                  Custom Packaging
                </li>
                <li className="stagger-item">
                  <span className="feature-icon">✨</span>
                  Add Special Decorations
                </li>
              </ul>
              <Link to="/custom-order" className="custom-order-button stagger-item">
                Start Designing
                <span className="button-arrow">→</span>
              </Link>
            </div>
            <div className="custom-order-image stagger-item">
              <div className="image-container">
                <div className="floating-elements">
                  <div className="float-item candle-1"></div>
                  <div className="float-item candle-2"></div>
                  <div className="float-item flower-1">🌸</div>
                  <div className="float-item flower-2">🌺</div>
                  <div className="float-item star-1">✨</div>
                  <div className="float-item star-2">✨</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Mission Statement Section */}
      <section className="mission-section split-reveal">
        <div className="section-container">
          <div className="mission-content">
            <div className="mission-text stagger-item">
              <h2>Our Mission</h2>
              <div className="decorative-line"></div>
              <p>
                At Nard Candles, we believe in creating a calming and serene environment in every home.
                Our mission is to provide high-quality, eco-friendly candles that elevate your space
                and enhance your well-being.
              </p>
              <div className="mission-values">
                <div className="value-item stagger-item">
                  <span className="value-icon">🌿</span>
                  <h3>Sustainability</h3>
                  <p>Eco-friendly materials and processes</p>
                </div>
                <div className="value-item stagger-item">
                  <span className="value-icon">💝</span>
                  <h3>Quality</h3>
                  <p>Premium ingredients and craftsmanship</p>
                </div>
                <div className="value-item stagger-item">
                  <span className="value-icon">🏡</span>
                  <h3>Comfort</h3>
                  <p>Creating peaceful home environments</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Company History Section */}
      <section className="story-section slide-in">
        <div className="section-container">
          <div className="story-content">
            <div className="story-image stagger-item">
              <div className="image-frame">
                <div className="floating-elements">
                  <div className="story-element candle-making"></div>
                  <div className="story-element workshop"></div>
                  <div className="story-element nature"></div>
                </div>
              </div>
            </div>
            <div className="story-text stagger-item">
              <h2>Our Story</h2>
              <div className="decorative-line"></div>
              <div className="timeline">
                <div className="timeline-item stagger-item">
                  <span className="year">2020</span>
                  <p>Founded as a small passion project in a home kitchen</p>
                </div>
                <div className="timeline-item stagger-item">
                  <span className="year">2021</span>
                  <p>Expanded to our first workshop</p>
                </div>
                <div className="timeline-item stagger-item">
                  <span className="year">2022</span>
                  <p>Launched our eco-friendly product line</p>
                </div>
                <div className="timeline-item stagger-item">
                  <span className="year">2023</span>
                  <p>Grew into a beloved brand known for quality and sustainability</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* USP Section */}
      <section className="usp-section split-reveal">
        <div className="section-container">
          <h2 className="stagger-item">Why Choose Us?</h2>
          <div className="decorative-line"></div>
          <div className="usp-grid">
            <div className="usp-item stagger-item">
              <div className="usp-icon">
                <span>🌿</span>
              </div>
              <h3>100% Natural Ingredients</h3>
              <p>Pure, sustainable materials for a clean burn</p>
              <div className="hover-info">
                <ul>
                  <li>Soy and beeswax blend</li>
                  <li>Essential oil fragrances</li>
                  <li>Cotton wicks</li>
                </ul>
              </div>
            </div>
            <div className="usp-item stagger-item">
              <div className="usp-icon">
                <span>🎨</span>
              </div>
              <h3>Handcrafted with Love</h3>
              <p>Each candle is uniquely made with care</p>
              <div className="hover-info">
                <ul>
                  <li>Small batch production</li>
                  <li>Artisanal techniques</li>
                  <li>Quality control</li>
                </ul>
              </div>
            </div>
            <div className="usp-item stagger-item">
              <div className="usp-icon">
                <span>♻️</span>
              </div>
              <h3>Eco-friendly Packaging</h3>
              <p>Sustainable and recyclable materials</p>
              <div className="hover-info">
                <ul>
                  <li>Recycled materials</li>
                  <li>Minimal waste</li>
                  <li>Reusable containers</li>
                </ul>
              </div>
            </div>
            <div className="usp-item stagger-item">
              <div className="usp-icon">
                <span>🌺</span>
              </div>
              <h3>Wide Range of Scents</h3>
              <p>Something for every preference</p>
              <div className="hover-info">
                <ul>
                  <li>Seasonal collections</li>
                  <li>Classic fragrances</li>
                  <li>Custom blends</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <div className="section-container">
        <Link to="/register" className="register-link fade-in-up">
          <button className="register-button stagger-item">
            Register for Training
          </button>
        </Link>
      </div>
    </div>
  );
};

export default Home;
