import { Route, Routes } from 'react-router-dom';
import Header from './components/header/Header'; // Adjust the path as necessary
import Footer from './components/header/Footer'; // Adjust the path as necessary
import Home from './pages/Home';
import Products from './pages/Products';
import CustomOrders from './pages/CustomOrders';
import Post from './pages/post';
import Contact from './pages/Contact';
import About from './pages/About';
import TermsOfService from './components/header/TermsOfService';
import PrivacyPolicy from './components/header/PrivacyPolicy';
import MyCart from './pages/MyCart';
import RegistrationPage from "./pages/RegistrationPage";
import MyOrders from './pages/MyOrders';
import Register from './pages/Register';
import Profile from './pages/Profile';
import EmailTest from './pages/EmailTest';
import AuthProvider from './context/AuthContext';

function App() {
  return (
    <AuthProvider>
      <Header />
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/products" element={<Products />} />
        <Route path="/custom-orders" element={<CustomOrders />} />
        <Route path="/blog" element={<Post />} />
        <Route path="/contact" element={<Contact />} />
        <Route path="/about" element={<About />} />
        <Route path="/terms" element={<TermsOfService />} />
        <Route path="/privacy" element={<PrivacyPolicy />} />
        <Route path="/my-cart" element={<MyCart />} />
        <Route path="/register" element={<RegistrationPage />} />
        <Route path="/my-orders" element={<MyOrders />} />
        <Route path="/register" element={<Register />} />
        <Route path="/profile" element={<Profile />} />
        <Route path="/email-test" element={<EmailTest />} />
      </Routes>
      <Footer />
    </AuthProvider>
  );
}

export default App;
