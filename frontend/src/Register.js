import React, { useState } from 'react';

const Register = () => {
    const [formData, setFormData] = useState({ username: '', password: '' });
    const [successMessage, setSuccessMessage] = useState('');
    const [error, setError] = useState('');

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = await fetch('http://localhost:8000/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData),
            });
            if (!response.ok) throw new Error('Erro ao cadastrar usuário. Verifique os dados.');
            setSuccessMessage('Usuário cadastrado com sucesso!');
            setError('');
        } catch (err) {
            setError(err.message);
            setSuccessMessage('');
        }
    };

    return (
        <div style={{ maxWidth: '300px', margin: 'auto', textAlign: 'center' }}>
            <h2>Cadastrar Usuário</h2>
            {successMessage && <p style={{ color: 'green' }}>{successMessage}</p>}
            {error && <p style={{ color: 'red' }}>{error}</p>}
            <form onSubmit={handleSubmit}>
                <input
                    type="text"
                    name="username"
                    placeholder="Usuário"
                    value={formData.username}
                    onChange={handleChange}
                    required
                />
                <input
                    type="password"
                    name="password"
                    placeholder="Senha"
                    value={formData.password}
                    onChange={handleChange}
                    required
                />
                <button type="submit">Cadastrar</button>
            </form>
            <button
                onClick={() => (window.location.href = '/')}
                style={{ marginTop: '20px' }}
            >
                Voltar ao Login
            </button>
        </div>
    );
};

export default Register;
