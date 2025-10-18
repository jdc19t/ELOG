<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: Index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELOG S.A. INVENTORY MANAGEMENT</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: rgba(255, 255, 255, 1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }
        

        .header h1 {
            color: #667eea;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .controls {
            background: rgba(255, 255, 255, 1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .controls-row {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 15px 50px 15px 20px;
            border: 2px solid #bab1b1ce;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 1);
        }

        .search-box input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .btn {
            padding: 15px 25px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .btn2 {
            padding: 10px 10px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, #7289f3ff, #764ba2);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 1);
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }

        .product-table {
            background: rgba(255, 255, 255, 1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        th, td {
            padding: 18px;
            text-align: left;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        th {
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tbody tr {
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background: rgba(102, 126, 234, 0.05);
            transform: scale(1.01);
        }

        .stock-low {
            color: #f80000;
            font-weight: bold;
        }

        .stock-medium {
            color: #ffa500;
            font-weight: bold;
        }

        .stock-good {
            color: #51cf66;
            font-weight: bold;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 20px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
        }

        .close {
            color: white;
            font-size: 2rem;
            font-weight: bold;
            cursor: pointer;
            padding: 0 10px;
        }

        .close:hover {
            opacity: 0.7;
        }

        .modal-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #a4a0a0ac;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 1);
        }

        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .header {
                flex-direction: column-reverse;
                align-items: center;
                text-align: center;
            }

            #Logo {
                margin-left: 0;
                margin-bottom: 20px;
            }

            .controls-row {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                min-width: auto;
            }

            table {
                font-size: 0.9rem;
            }

            th, td {
                padding: 12px 8px;
            }

            .modal-content {
                width: 95%;
                margin: 10% auto;
            }
        }

       .header-content {
            margin-left: 30px;
            order: 2; /* This will push the logo to the right */
        }
        
        #Logo img {
            max-width: 150px;
            height: auto;
            display: block;
        }
        
        .Logo {
            order: 1; /* This will keep the content on the left */
        }

        .btn-logout{
            font-size: 1rem; 
            color: #ffffffff; 
            background: linear-gradient(135deg, #ff0000ff, #f96b63ff);
            margin-top: 25px; 
            margin-block-end: 25px; 
            cursor: pointer; 
           
        }
        
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div id="Logo">
                <img src="assets/ELOG LOGO.jpg" alt="ELOG Logo">
            </div>
            
            <div class="header-content">
                <h1>ELOG S.A.</h1>
                <p>Inventory Management Prototype</p>
                <div id="lastSaved" style="font-size: 0.8rem; color: #666; margin-top: 10px;"></div>

                <button class="btn2 btn-logout";  onclick= "location.href='Index.php';">
                    Logout
                </button>

            </div>
        </div>

        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number" id="totalProducts">0</div>
                <div class="stat-label">Total Productos</div>
            </div>
            <div class="stat-card">
                <div style="color: #ff0d00;" class="stat-number" id="lowStockCount">0</div>
                <div style="color: #ff0d00;">Stock Bajo</div>
            </div>
            
            
        </div>

        <!-- Controls -->
        <div class="controls">
            <div class="controls-row">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Buscar productos...">
                    <span class="search-icon">🔍</span>
                </div>
                
                <button class="btn btn-secondary" onclick="exportData()">
                    📊 Exportar Json/CSV
                </button>
                <button class="btn btn-secondary" onclick="importData()" style="background: linear-gradient(135deg, #51cf66, #40c057); color: white;">
                    📥 Importar Datos
                </button>
                
            </div>

            <!-- FILTER CONTROLS -->
            <div class="controls-row">
                <select id="categoryFilter">
                    <option value="">Proveedor</option>
                    <option value="Carga Liviana ELOG">Carga Liviana ELOG</option>
                    <option value="Carga Media ELOG">Carga Media ELOG</option>
                    <option value="Puertas ELOG">Puertas ELOG</option>
                    <option value="Maya">Maya</option>
                    <option value="Arquense">Arquense</option>
                    <option value="ELOG">ELOG</option>
                    <option value="Alapont">Alapont</option>
                    <option value="Merik">Merik</option>
                    <option value="Kimer">Kimer</option>
                    <option value="ATOX">ATOX</option>
                    <option value="Polypal">Polypal</option>
                    <option value="Mecalux">Mecalux</option>
                    <option value="AR">AR</option>
                    <option value="Correagua">Correagua</option>
                    <option value="Desconocido">Desconocido</option>
                </select>

                <select id="stockFilter">
                    <option value="">Todos los stocks</option>
                    <option value="low">Stock bajo (≤10)</option>
                    <option value="medium">Stock medio (11-50)</option>
                    <option value="high">Stock alto (>50)</option>
                </select>

                <select id="stateFilter">
                    <option value="">Estado</option>
                    <option value="Nuevo">Nuevo</option>
                    <option value="Segunda">Segunda</option>
                    <option value="Mal Estado">Mal Estado</option>
                    <option value="Revisar Estado">Revisar Estado</option>
                </select>

                <!-- reset filters

                <button id="resetFilters">
                    Reset Filters
                </button>

                -->

            </div>
        </div>

        <!-- Product Table -->
        <div class="product-table">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Proveedor</th>    
                        <th>Estado</th>
                        <th>Cantidad</th>
                        
                        
                        <th>Acciones</th>
                        
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <!-- Products will be inserted here -->
                </tbody>
            </table>
            <div id="emptyState" class="empty-state" style="display: none;">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7ZM9 3V4H15V3H9ZM7 6V19H17V6H7Z"/>
                </svg>
                <h3>No hay productos en el inventario</h3>
                <p>Comienza agregando tu primer producto</p>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Agregar Producto</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <div class="form-group">
                        <label for="productName">Nombre del Producto *</label>
                        <input type="text" id="productName" required>
                    </div>
    <!-- ADDING PRODUCTS -->
                    <div class="form-group">
                        <label for="productCategory">Proveedor *</label>
                        <select id="productCategory" required>
                        <option value="">Proveedor</option>
                        <option value="Carga Liviana ELOG">Carga Liviana ELOG</option>
                        <option value="Carga Media ELOG">Carga Media ELOG</option>
                        <option value="Puertas ELOG">Puertas ELOG</option>
                        <option value="Maya">Maya</option>
                        <option value="Arquense">Arquense</option>
                        <option value="ELOG">ELOG</option>
                        <option value="Alapont">Alapont</option>
                        <option value="Merik">Merik</option>
                        <option value="Kimer">Kimer</option>
                        <option value="ATOX">ATOX</option>
                        <option value="Polypal">Polypal</option>
                        <option value="Mecalux">Mecalux</option>
                        <option value="AR">AR</option>
                        <option value="Correagua">Correagua</option>
                        <option value="Desconocido">Desconocido</option>
                            
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="productState">Estado *</label>
                        <select id="productState" required>
                        <option value="">Seleccionar Estado</option>
                        <option value="Nuevo">Nuevo</option>
                        <option value="Segunda">Segunda</option>
                        <option value="Mal Estado">Mal Estado</option>
                        <option value="Revisar Estado">Revisar Estado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="productQuantity">Cantidad *</label>
                        <input type="number" id="productQuantity" min="0" required>
                    </div>
                   
                    <div class="form-group">
                        <label for="productDescription">Descripción</label>
                        <textarea id="productDescription" rows="3" placeholder="Descripción opcional del producto"></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Guardar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let products = [];
        let editingIndex = -1;

        // Storage functions using hidden form technique for persistence
        function saveToStorage() {
            const data = {
                products: products,
                lastUpdated: new Date().toISOString()
            };
            
            try {
                const jsonData = JSON.stringify(data);
                
                // Create or update hidden storage element
                let storageElement = document.getElementById('hiddenStorage');
                if (!storageElement) {
                    storageElement = document.createElement('input');
                    storageElement.type = 'hidden';
                    storageElement.id = 'hiddenStorage';
                    storageElement.style.display = 'none';
                    document.body.appendChild(storageElement);
                }
                storageElement.value = jsonData;
                
                // Also try to store in URL hash as backup
                const encodedData = btoa(jsonData).replace(/[+/=]/g, function(match) {
                    return {'+': '-', '/': '_', '=': ''}[match];
                });
                
                // Store in a meta tag for persistence
                let metaStorage = document.querySelector('meta[name="inventory-data"]');
                if (!metaStorage) {
                    metaStorage = document.createElement('meta');
                    metaStorage.name = 'inventory-data';
                    document.head.appendChild(metaStorage);
                }
                metaStorage.content = encodedData;
                
                console.log('Datos guardados en múltiples ubicaciones');
                updateLastSavedIndicator(data.lastUpdated);
                showSaveNotification();
                
            } catch (error) {
                console.error('Error al guardar:', error);
                // Ultimate fallback - store in global variable
                window.persistentInventoryData = data;
                updateLastSavedIndicator(data.lastUpdated);
            }
        }

        function loadFromStorage() {
            try {
                // Method 1: Try hidden input element
                const storageElement = document.getElementById('hiddenStorage');
                if (storageElement && storageElement.value) {
                    const data = JSON.parse(storageElement.value);
                    if (data && data.products && Array.isArray(data.products)) {
                        products = data.products;
                        console.log('Datos cargados desde elemento oculto');
                        updateLastSavedIndicator(data.lastUpdated);
                        showLoadNotification(data.products.length);
                        return true;
                    }
                }
                
                // Method 2: Try meta tag
                const metaStorage = document.querySelector('meta[name="inventory-data"]');
                if (metaStorage && metaStorage.content) {
                    try {
                        const decodedData = atob(metaStorage.content.replace(/[-_]/g, function(match) {
                            return {'-': '+', '_': '/'}[match];
                        }));
                        const data = JSON.parse(decodedData);
                        if (data && data.products && Array.isArray(data.products)) {
                            products = data.products;
                            console.log('Datos cargados desde meta tag');
                            updateLastSavedIndicator(data.lastUpdated);
                            showLoadNotification(data.products.length);
                            return true;
                        }
                    } catch (e) {
                        console.log('Error decodificando meta tag:', e);
                    }
                }
                
                // Method 3: Global variable fallback
                if (window.persistentInventoryData && window.persistentInventoryData.products) {
                    products = window.persistentInventoryData.products;
                    console.log('Datos cargados desde variable global');
                    updateLastSavedIndicator(window.persistentInventoryData.lastUpdated);
                    showLoadNotification(products.length);
                    return true;
                }
                
            } catch (error) {
                console.error('Error al cargar:', error);
            }
            
            return false;
        }

        function showSaveNotification() {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #51cf66, #40c057);
                color: white;
                padding: 15px 20px;
                border-radius: 10px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.2);
                z-index: 10000;
                font-weight: 600;
                animation: slideIn 0.3s ease;
            `;
            notification.innerHTML = '✅ Datos guardados correctamente';
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 2000);
        }

        function showLoadNotification(count) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: white;
                padding: 15px 20px;
                border-radius: 10px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.2);
                z-index: 10000;
                font-weight: 600;
                animation: slideIn 0.3s ease;
            `;
            notification.innerHTML = `📦 ${count} productos cargados`;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 2000);
        }

        function updateLastSavedIndicator(timestamp) {
            const lastSavedElement = document.getElementById('lastSaved');
            if (timestamp) {
                const date = new Date(timestamp);
                const formattedDate = date.toLocaleString('es-ES', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                lastSavedElement.textContent = `Última actualización: ${formattedDate}`;
            } else {
                lastSavedElement.textContent = '';
            }
        }

        // Initialize with sample data
        function initializeSampleData() {
            products = [
              
            ];
            saveToStorage(); // Save sample data
            updateDisplay();
        }

        function initializeApp() {
            // Try to load existing data first
            if (!loadFromStorage()) {
                // If no data exists, initialize with sample data
                initializeSampleData();
            } else {
                // Data was loaded, just update display
                updateDisplay();
            }
        }

        function updateDisplay() {
            updateTable();
            updateStats();
        }

        function updateStats() {
            const totalProducts = products.length;
            const lowStockCount = products.filter(p => p.quantity <= 10).length;
            
           

            document.getElementById('totalProducts').textContent = totalProducts;
            document.getElementById('lowStockCount').textContent = lowStockCount;
            
            document.getElementById('totalQuantity').textContent = totalQuantity.toLocaleString();
        }

        function updateTable() {
            const tbody = document.getElementById('productTableBody');
            const emptyState = document.getElementById('emptyState');
            
            // Apply filters
            let filteredProducts = products.filter(product => {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                const categoryFilter = document.getElementById('categoryFilter').value;
                const stockFilter = document.getElementById('stockFilter').value;
                const stateFilter = document.getElementById('stateFilter').value;
                const matchesSearch = product.name.toLowerCase().includes(searchTerm) ||
                                    product.category.toLowerCase().includes(searchTerm);
                const matchesCategory = !categoryFilter || product.category === categoryFilter;
                let matchesStock = true;
                if (stockFilter === 'low') matchesStock = product.quantity <= 10;
                else if (stockFilter === 'medium') matchesStock = product.quantity > 10 && product.quantity <= 50;
                else if (stockFilter === 'high') matchesStock = product.quantity > 50;
                const matchesState = !stateFilter || product.state === stateFilter;
                return matchesSearch && matchesCategory && matchesStock && matchesState;
            });

            if (filteredProducts.length === 0) {
                tbody.innerHTML = '';
                emptyState.style.display = 'block';
                return;
            }

            emptyState.style.display = 'none';
            tbody.innerHTML = filteredProducts.map((product, index) => {
                const originalIndex = products.indexOf(product);
                const stockClass = product.quantity <= 10 ? 'stock-low' : 
                                 product.quantity <= 50 ? 'stock-medium' : 'stock-good';
                

                return `
                    <tr>
                        <td>
                            <strong>${product.name}</strong>
                            ${product.description ? `<br><small style="color: #666;">${product.description}</small>` : ''}
                        </td>
                        <td>${product.category}</td>
                        <td>${product.state}</td>
                        <td class="${stockClass}">${product.quantity}</td>
                        
                        
                        <td>
                            <button class="btn btn-secondary" onclick="editProduct(${originalIndex})" 
                                    style="padding: 8px 12px; font-size: 0.85rem; margin-right: 5px;">
                                ✏️ Editar
                            </button>
                          
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function openModal(index = -1) {
            editingIndex = index;
            const modal = document.getElementById('productModal');
            const modalTitle = document.getElementById('modalTitle');
            const form = document.getElementById('productForm');
            
            if (index >= 0) {
                modalTitle.textContent = 'Editar Producto';
                const product = products[index];
                document.getElementById('productName').value = product.name;
                document.getElementById('productCategory').value = product.category;
                document.getElementById('productState').value = product.state || '';
                document.getElementById('productQuantity').value = product.quantity;
               
                document.getElementById('productDescription').value = product.description || '';
                document.getElementById('submitBtn').textContent = 'Actualizar Producto';
            } else {
                modalTitle.textContent = 'Agregar Producto';
                form.reset();
                document.getElementById('submitBtn').textContent = 'Guardar Producto';
            }
            
            modal.style.display = 'block';
        }

        function closeModal() {
            document.getElementById('productModal').style.display = 'none';
            editingIndex = -1;
        }

        function editProduct(index) {
            openModal(index);
        }

        function deleteProduct(index) {
            if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                products.splice(index, 1);
                saveToStorage(); // Save after deletion
                updateDisplay();
            }
        }

        function exportData() {
        const csvContent = "data:text/csv;charset=utf-8," + 
                "Nombre,Categoría,Cantidad,Precio,Total,Descripción\n" +
                products.map(p => 
                    `"${p.name}","${p.category}",${p.quantity},${p.price},${p.quantity * p.price},"${p.description || ''}"`
                ).join("\n");
           
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `inventario_${new Date().toISOString().split('T')[0]}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        
            
            // JSON backup
            const jsonData = JSON.stringify({products: products, exportDate: new Date().toISOString()}, null, 2);
            const jsonBlob = new Blob([jsonData], {type: 'application/json'});
            const jsonUrl = URL.createObjectURL(jsonBlob);
            const jsonLink = document.createElement("a");
            jsonLink.href = jsonUrl;
            const now = new Date();
            const dateString = now.toISOString().split('T')[0]; // YYYY-MM-DD
            const timeString = now.toTimeString().split(' ')[0].replace(/:/g, '-'); // HH-MM-SS
            jsonLink.download = `inventario_backup_${dateString}_${timeString}.json`;
            document.body.appendChild(jsonLink);
            jsonLink.click();
            document.body.removeChild(jsonLink);
            URL.revokeObjectURL(jsonUrl);
            
            alert('✅ Datos JSON backup');
        }

        function importData() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = '.json,.csv';
            input.onchange = function(event) {
                const file = event.target.files[0];
                if (!file) return;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        if (file.name.endsWith('.json')) {
                            const data = JSON.parse(e.target.result);
                            if (data.products && Array.isArray(data.products)) {
                                if (confirm(`¿Deseas importar ${data.products.length} productos? Esto reemplazará los datos actuales.`)) {
                                    products = data.products;
                                    saveToStorage();
                                    updateDisplay();
                                    alert('✅ Datos importados correctamente');
                                }
                            }
                        } else {
                            alert('Por ahora solo se soporta importación de archivos JSON. Usa "Exportar" para crear un backup compatible.');
                        }
                    } catch (error) {
                        alert('❌ Error al importar el archivo. Verifica que sea un backup válido.');
                        console.error('Error importing:', error);
                    }
                };
                reader.readAsText(file);
            };
            input.click();
        }

        function clearAllData() {
            if (confirm('⚠️ ¿Estás seguro de que deseas eliminar TODOS los productos?\n\nEsta acción no se puede deshacer.')) {
                products = [];
                
                // Clear all storage methods
                try {
                    // Clear hidden element
                    const storageElement = document.getElementById('hiddenStorage');
                    if (storageElement) {
                        storageElement.value = '';
                    }
                    
                    // Clear meta tag
                    const metaStorage = document.querySelector('meta[name="inventory-data"]');
                    if (metaStorage) {
                        metaStorage.content = '';
                    }
                    
                    // Clear global variable
                    window.persistentInventoryData = null;
                    
                } catch (error) {
                    console.error('Error al limpiar almacenamiento:', error);
                }
                
                updateDisplay();
                updateLastSavedIndicator(null);
                
                // Show confirmation
                setTimeout(() => {
                    alert('✅ Todos los datos han sido eliminados.');
                }, 100);
            }
        }

        // Event listeners
        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const product = {
                name: document.getElementById('productName').value,
                category: document.getElementById('productCategory').value,
                state: document.getElementById('productState').value,
                quantity: parseInt(document.getElementById('productQuantity').value),
                
                description: document.getElementById('productDescription').value
            };

            if (editingIndex >= 0) {
                products[editingIndex] = product;
            } else {
                products.push(product);
            }

            saveToStorage(); // Save after adding/editing
            updateDisplay();
            closeModal();
        });

        document.getElementById('searchInput').addEventListener('input', updateTable);
        document.getElementById('categoryFilter').addEventListener('change', updateTable);
        document.getElementById('stockFilter').addEventListener('change', updateTable);
        document.getElementById('stateFilter').addEventListener('change', updateTable);

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('productModal');
            if (event.target === modal) {
                closeModal();
            }
        });

        // Initialize the application
        initializeApp();
    </script>
</body>
</html>