import { useEffect, useState } from 'react';
import './Post.css';
import axios from 'axios';

const Post = () => {
  const [posts, setPosts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchPosts = async () => {
      try {
        setLoading(true);
        const response = await axios.get('http://127.0.0.1:8000/api/posts');
        setPosts(response.data);
        setError(null);
      } catch (error) {
        console.error("Error fetching posts:", error);
        setError("Failed to load posts. Please try again later.");
      } finally {
        setLoading(false);
      }
    };

    fetchPosts();
  }, []);

  // Function to get the thumbnail from the video link
  const getThumbnailFromVideo = (link) => {
    const youtubeRegex = /^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/(watch\?v=)?([a-zA-Z0-9_-]{11})/;
    const match = link.match(youtubeRegex);
    if (match) {
      const videoId = match[5];
      return `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg`; // Highest quality thumbnail
    }
    return '/images/default-post.jpg'; // Fallback image
  };

  // Function to format date
  const formatDate = (dateString) => {
    const options = { 
      year: 'numeric', 
      month: 'long', 
      day: 'numeric'
    };
    return new Date(dateString).toLocaleDateString('en-US', options);
  };

  if (loading) {
    return (
      <div className="post-container">
        <h1>Latest Posts</h1>
        <div className="loading-posts">
          <div className="loading-spinner"></div>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="post-container">
        <h1>Latest Posts</h1>
        <div className="no-posts">
          <i className="fas fa-exclamation-circle"></i>
          <p>{error}</p>
        </div>
      </div>
    );
  }

  return (
    <div className="post-container">
      <h1>Latest Posts</h1>
      <div className="post-grid">
        {posts.length > 0 ? posts.map((post) => (
          <div key={post.id} className="post-card">
            <img 
              src={post.media && post.media.trim() !== '' ? post.media : getThumbnailFromVideo(post.link)} 
              alt={post.title}
              onError={(e) => {
                e.target.src = '/images/default-post.jpg';
              }}
            />
            <div className="post-content">
              <div>
                <h2>{post.title}</h2>
                <p className="post-meta">
                  <i className="far fa-user"></i>
                  <span>{post.author || 'Admin'}</span>
                  <i className="far fa-calendar-alt"></i>
                  <span>{formatDate(post.created_at)}</span>
                </p>
                <p className="post-description">{post.short_description}</p>
              </div>
              <a 
                href={post.link} 
                className="view-post-button" 
                target="_blank" 
                rel="noopener noreferrer"
              >
                View Post
                <i className="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>
        )) : (
          <div className="no-posts">
            <i className="far fa-newspaper"></i>
            <p>No posts available at the moment. Check back soon for updates!</p>
          </div>
        )}
      </div>
    </div>
  );
};

export default Post;
