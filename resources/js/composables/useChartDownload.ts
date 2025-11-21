export function useChartDownload() {
    const downloadChartAsPng = (elementId: string, fileName: string) => {
        const element = document.getElementById(elementId);
        if (!element) {
            console.error('Element not found');
            return;
        }

        try {
            // Buscar el canvas dentro del elemento
            const canvas = element.querySelector('canvas');
            if (!canvas) {
                console.error('Canvas not found');
                return;
            }

            // Crear un nuevo canvas con fondo blanco
            const tempCanvas = document.createElement('canvas');
            const ctx = tempCanvas.getContext('2d');
            
            tempCanvas.width = canvas.width;
            tempCanvas.height = canvas.height;
            
            if (ctx) {
                // Fondo blanco
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
                
                // Dibujar el gráfico encima
                ctx.drawImage(canvas, 0, 0);
            }

            // Descargar
            const link = document.createElement('a');
            link.download = `${fileName}.png`;
            link.href = tempCanvas.toDataURL('image/png');
            link.click();
        } catch (error) {
            console.error('Error generating image:', error);
        }
    };

    return {
        downloadChartAsPng,
    };
}
