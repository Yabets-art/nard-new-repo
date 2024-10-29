import { useEffect, useState } from 'react';
import './Post.css';
import axios from 'axios';

const Post = () => {
  const [posts, setPosts] = useState([]);

  // Fetch posts from the backend when the component loads
  useEffect(() => {
    axios.get('http://127.0.0.1:8000/api/posts')  // Replace with your backend URL
      .then(response => {
        setPosts(response.data); // Assuming the data is sent as JSON
      })
      .catch(error => {
        console.error("There was an error fetching the posts!", error);
      });
  }, []);

  // Function to get the thumbnail from the video link
  const getThumbnailFromVideo = (link) => {
    const youtubeRegex = /^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/(watch\?v=)?([a-zA-Z0-9_-]{11})/;
    const match = link.match(youtubeRegex);
    if (match) {
      const videoId = match[5];
      return `https://img.youtube.com/vi/${videoId}/hqdefault.jpg`; // High-quality thumbnail
    }
    return 'default-image.jpg'; // Fallback image if link is not a YouTube link
  };

  return (
    <div className="post-container">
      <h1>Latest Posts</h1>
      <div className="post-grid">
        {posts.length > 0 ? posts.map((post) => (
          <div key={post.id} className="post-card">
            <img 
              src={post.media && post.media.trim() !== '' ? post.media : getThumbnailFromVideo(post.link)} 
              alt={post.title} 
            />
            <div className="post-content">
              <h2>{post.title}</h2>
              <p className="post-meta">
                {post.author ? `By ${post.author}` : 'By Admin'} on {new Date(post.created_at).toLocaleDateString()}
              </p>
              <p className="post-description">{post.short_description}</p>
              <a href={post.link} className="view-post-button" target="_blank" rel="noopener noreferrer">
                View Post
              </a>
            </div>
          </div>
        )) : (
          <p>No posts available at the moment.</p>
        )}
      </div>
    </div>
  );
};

export default Post;
