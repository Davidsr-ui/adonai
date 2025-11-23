import React, { useState } from "react";
import { Search, Calendar, Award, Trophy, BookOpen, Users, Sparkles, X, Filter } from "lucide-react";

export default function BlogEscolar() {
  const [selectedPost, setSelectedPost] = useState(null);
  const [selectedCategory, setSelectedCategory] = useState("Todos");
  const [searchTerm, setSearchTerm] = useState("");

  const categories = [
    { name: "Todos", icon: Sparkles, color: "bg-purple-500" },
    { name: "Concursos", icon: Trophy, color: "bg-yellow-500" },
    { name: "Premios", icon: Award, color: "bg-green-500" },
    { name: "Académico", icon: BookOpen, color: "bg-blue-500" },
    { name: "Eventos", icon: Calendar, color: "bg-pink-500" },
    { name: "Comunidad", icon: Users, color: "bg-indigo-500" },
  ];

  const posts = [
    {
      id: 1,
      date: "15 Enero 2025",
      title: "🎉 Gran Premio en Olimpiadas de Matemáticas",
      excerpt: "Nuestro equipo obtuvo el primer lugar en las Olimpiadas Nacionales de Matemáticas, representando con orgullo a nuestra institución.",
      fullContent: "Es con inmensa alegría que anunciamos que nuestro equipo de matemáticas ha obtenido el primer lugar en las Olimpiadas Nacionales de Matemáticas 2025. Los estudiantes María González, Pedro Ramírez y Ana Torres demostraron su excepcional talento resolviendo complejos problemas matemáticos. Este logro es el resultado de meses de preparación, dedicación y trabajo en equipo. Felicitamos a nuestros campeones y a su profesora coordinadora, la Lic. Carmen Flores, quien los guió durante todo el proceso.",
      image: "https://images.unsplash.com/photo-1596496050827-8299e0220de1?w=800&h=500&fit=crop",
      category: "Premios",
      author: "Dirección Académica",
      tags: ["Matemáticas", "Olimpiadas", "Logros"]
    },
    {
      id: 2,
      date: "12 Enero 2025",
      title: "🏆 Convocatoria: Concurso de Oratoria 2025",
      excerpt: "Invitamos a todos los estudiantes de 4to a 6to grado a participar en nuestro tradicional Concurso de Oratoria. ¡Inscripciones abiertas!",
      fullContent: "La Escuela 'Santa Rosa de Lima' convoca al tradicional Concurso de Oratoria 2025. Este evento busca desarrollar las habilidades comunicativas de nuestros estudiantes y fomentar la expresión oral con claridad y confianza. Categorías: 4to grado, 5to grado y 6to grado. Tema libre relacionado con valores, medio ambiente o cultura. Inscripciones hasta el 25 de enero. Fecha del concurso: 15 de febrero. Premios: Medallas de oro, plata y bronce para cada categoría, además de diplomas de reconocimiento. Para más información contactar a la Prof. Sandra Méndez en la coordinación académica.",
      image: "https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800&h=500&fit=crop",
      category: "Concursos",
      author: "Coord. Académica",
      tags: ["Oratoria", "Concurso", "Inscripciones"]
    },
    {
      id: 3,
      date: "10 Enero 2025",
      title: "📚 Inicio del Programa de Lectura 2025",
      excerpt: "Lanzamos nuestro nuevo programa 'Leer para Crecer' con actividades semanales, club de lectura y biblioteca renovada.",
      fullContent: "Con gran entusiasmo inauguramos el programa 'Leer para Crecer' 2025, una iniciativa que busca fomentar el amor por la lectura en todos nuestros estudiantes. El programa incluye: sesiones de cuentacuentos cada viernes, club de lectura mensual, biblioteca renovada con más de 500 nuevos títulos, y concurso del mejor lector del mes. Los estudiantes podrán elegir libros según su nivel y preferencias. Además, cada mes premiaremos al estudiante que más libros haya leído con un diploma y un libro sorpresa. ¡Los invitamos a sumarse a esta hermosa aventura literaria!",
      image: "https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=800&h=500&fit=crop",
      category: "Académico",
      author: "Biblioteca Escolar",
      tags: ["Lectura", "Programa", "Educación"]
    },
    {
      id: 4,
      date: "08 Enero 2025",
      title: "🎨 Festival Cultural Intercultural",
      excerpt: "Celebramos la diversidad con presentaciones de danzas, música tradicional, gastronomía y exposiciones artísticas de todas las regiones del Perú.",
      fullContent: "El próximo 20 de febrero realizaremos nuestro Festival Cultural Intercultural, un evento que celebra la riqueza y diversidad de nuestro Perú. Cada grado representará una región diferente del país, mostrando sus danzas típicas, música tradicional, trajes típicos y gastronomía. Habrá stands de comida, exposiciones de arte y manualidades, y presentaciones en vivo en nuestro patio principal. Este evento es una oportunidad maravillosa para que nuestros estudiantes aprendan sobre la diversidad cultural de nuestra nación y desarrollen un sentido de identidad y orgullo por nuestras raíces. Invitamos a todas las familias a acompañarnos.",
      image: "https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=800&h=500&fit=crop",
      category: "Eventos",
      author: "Coordinación Cultural",
      tags: ["Cultura", "Festival", "Diversidad"]
    },
    {
      id: 5,
      date: "05 Enero 2025",
      title: "🌟 Reconocimiento a Estudiantes Destacados",
      excerpt: "Felicitamos a los 15 estudiantes que obtuvieron el cuadro de honor durante el ciclo anterior por su excelencia académica.",
      fullContent: "Es un honor reconocer a los 15 estudiantes que han sido incluidos en el Cuadro de Honor del último ciclo escolar por su destacado desempeño académico. Estos estudiantes han demostrado dedicación, esfuerzo y amor por el aprendizaje, obteniendo calificaciones sobresalientes en todas sus áreas. Los homenajeados son: Ana Pérez (1er grado), Carlos Mendoza (1er grado), Lucía Vargas (2do grado), entre otros. Cada uno recibirá un diploma de reconocimiento y una medalla de honor en nuestra ceremonia especial del 30 de enero. ¡Felicitaciones a todos ellos y a sus familias por este logro!",
      image: "https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&h=500&fit=crop",
      category: "Premios",
      author: "Dirección General",
      tags: ["Reconocimiento", "Estudiantes", "Excelencia"]
    },
    {
      id: 6,
      date: "03 Enero 2025",
      title: "🤝 Proyecto de Solidaridad Comunitaria",
      excerpt: "Lanzamos nuestro proyecto anual de ayuda a familias de la comunidad con campañas de recolección y actividades solidarias.",
      fullContent: "Iniciamos nuestro Proyecto de Solidaridad Comunitaria 2025, una iniciativa que busca formar ciudadanos comprometidos con su comunidad. Durante este año realizaremos campañas de recolección de útiles escolares, ropa y alimentos no perecibles para familias necesitadas de nuestra zona. También organizaremos jornadas de limpieza en parques locales, visitas a asilos de ancianos, y actividades recreativas para niños de comunidades vecinas. Queremos que nuestros estudiantes desarrollen valores de empatía, solidaridad y responsabilidad social. Invitamos a toda la comunidad educativa a sumarse a esta noble causa.",
      image: "https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=800&h=500&fit=crop",
      category: "Comunidad",
      author: "Depto. Pastoral",
      tags: ["Solidaridad", "Comunidad", "Valores"]
    },
    {
      id: 7,
      date: "28 Diciembre 2024",
      title: "🔬 Nueva Sala de Ciencias Inaugurada",
      excerpt: "Modernizamos nuestra infraestructura con un nuevo laboratorio de ciencias equipado con tecnología de última generación.",
      fullContent: "Con gran orgullo inauguramos nuestra nueva Sala de Ciencias, un espacio moderno y completamente equipado donde nuestros estudiantes podrán realizar experimentos y desarrollar el pensamiento científico. El laboratorio cuenta con microscopios digitales, kits de química, material de biología, equipos de física y tablets para investigación. Este proyecto representa una inversión significativa en la educación de nuestros estudiantes y refuerza nuestro compromiso con una enseñanza de calidad. Agradecemos a todos los colaboradores que hicieron posible este sueño. Las clases en el nuevo laboratorio iniciarán la próxima semana.",
      image: "https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=800&h=500&fit=crop",
      category: "Académico",
      author: "Dirección General",
      tags: ["Infraestructura", "Ciencias", "Tecnología"]
    },
    {
      id: 8,
      date: "20 Diciembre 2024",
      title: "⚽ Torneo Deportivo Interescolar 2025",
      excerpt: "Abrimos inscripciones para nuestro torneo anual de fútbol, voleibol y atletismo. ¡Ven y representa a tu grado!",
      fullContent: "La Escuela 'Santa Rosa de Lima' invita a todos los estudiantes a participar en el Torneo Deportivo Interescolar 2025. Disciplinas: fútbol (varones y damas), voleibol y atletismo (100m, 200m, salto largo). Fechas: Del 5 al 15 de marzo. Inscripciones: Hasta el 28 de febrero en el área de educación física. Cada grado formará sus equipos representativos. Habrá premiación para los primeros tres lugares de cada categoría. Este torneo busca promover el deporte, el trabajo en equipo y la vida saludable. ¡Tu participación es importante! Consulta los horarios con el Prof. Miguel Sánchez.",
      image: "https://images.unsplash.com/photo-1551958219-acbc608c6377?w=800&h=500&fit=crop",
      category: "Concursos",
      author: "Área de Deportes",
      tags: ["Deportes", "Torneo", "Competencia"]
    },
  ];

  const filteredPosts = posts.filter(post => {
    const matchesCategory = selectedCategory === "Todos" || post.category === selectedCategory;
    const matchesSearch = post.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
                        post.excerpt.toLowerCase().includes(searchTerm.toLowerCase()) ||
                        post.tags.some(tag => tag.toLowerCase().includes(searchTerm.toLowerCase()));
    return matchesCategory && matchesSearch;
  });

  const CategoryIcon = ({ category }) => {
    const cat = categories.find(c => c.name === category);
    const Icon = cat ? cat.icon : Sparkles;
    return <Icon className="w-4 h-4" />;
  };

  return (
    <>
      <section className="w-full min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-16">
        <div className="max-w-7xl mx-auto px-4">
          {/* Header */}
          <div className="text-center mb-12">
            <div className="inline-flex items-center gap-2 px-5 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full font-semibold mb-4 shadow-lg">
              <Sparkles className="w-5 h-5" />
              <span>Blog Escolar</span>
            </div>
            <h1 className="text-5xl font-extrabold text-gray-900 mb-3 bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600">
              Noticias y Eventos
            </h1>
            <p className="text-gray-600 text-lg max-w-2xl mx-auto">
              Mantente informado sobre todas las actividades, concursos, premios y eventos de nuestra comunidad educativa
            </p>
          </div>

          {/* Search Bar */}
          <div className="mb-8 max-w-2xl mx-auto">
            <div className="relative">
              <Search className="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
              <input
                type="text"
                placeholder="Buscar noticias, eventos, concursos..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="w-full pl-12 pr-4 py-4 rounded-2xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-all shadow-sm"
              />
            </div>
          </div>

          {/* Categories */}
          <div className="mb-12">
            <div className="flex items-center gap-2 mb-4">
              <Filter className="w-5 h-5 text-gray-600" />
              <span className="font-semibold text-gray-700">Filtrar por categoría:</span>
            </div>
            <div className="flex flex-wrap gap-3">
              {categories.map((cat) => {
                const Icon = cat.icon;
                const isActive = selectedCategory === cat.name;
                return (
                  <button
                    key={cat.name}
                    onClick={() => setSelectedCategory(cat.name)}
                    className={`flex items-center gap-2 px-5 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 shadow-md ${
                      isActive
                        ? `${cat.color} text-white shadow-lg`
                        : "bg-white text-gray-700 hover:bg-gray-50"
                    }`}
                  >
                    <Icon className="w-5 h-5" />
                    <span>{cat.name}</span>
                  </button>
                );
              })}
            </div>
          </div>

          {/* Results Count */}
          <div className="mb-6 text-gray-600">
            Mostrando <span className="font-bold text-gray-800">{filteredPosts.length}</span> {filteredPosts.length === 1 ? 'noticia' : 'noticias'}
          </div>

          {/* Blog Grid */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {filteredPosts.map((post) => {
              const cat = categories.find(c => c.name === post.category);
              return (
                <article
                  key={post.id}
                  className="bg-white rounded-3xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer group"
                  onClick={() => setSelectedPost(post)}
                >
                  {/* Imagen */}
                  <div className="relative h-56 overflow-hidden">
                    <img
                      src={post.image}
                      alt={post.title}
                      className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                    
                    {/* Category Badge */}
                    <div className="absolute top-4 left-4">
                      <div className={`flex items-center gap-2 px-3 py-1.5 ${cat?.color || 'bg-gray-600'} text-white text-sm font-bold rounded-full shadow-lg`}>
                        <CategoryIcon category={post.category} />
                        <span>{post.category}</span>
                      </div>
                    </div>

                    {/* Date */}
                    <div className="absolute bottom-4 left-4 flex items-center gap-2 text-white">
                      <Calendar className="w-4 h-4" />
                      <span className="text-sm font-medium">{post.date}</span>
                    </div>
                  </div>

                  {/* Content */}
                  <div className="p-6">
                    <h3 className="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                      {post.title}
                    </h3>
                    <p className="text-gray-600 mb-4 line-clamp-3 text-sm leading-relaxed">
                      {post.excerpt}
                    </p>

                    {/* Tags */}
                    <div className="flex flex-wrap gap-2 mb-4">
                      {post.tags.slice(0, 2).map((tag, idx) => (
                        <span key={idx} className="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-lg">
                          #{tag}
                        </span>
                      ))}
                    </div>

                    {/* Author & CTA */}
                    <div className="flex items-center justify-between pt-4 border-t border-gray-100">
                      <span className="text-sm text-gray-500">{post.author}</span>
                      <button className="flex items-center gap-1 text-blue-600 font-semibold text-sm group-hover:gap-2 transition-all">
                        Leer más
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </article>
              );
            })}
          </div>

          {/* Empty State */}
          {filteredPosts.length === 0 && (
            <div className="text-center py-16">
              <div className="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <Search className="w-12 h-12 text-gray-400" />
              </div>
              <h3 className="text-2xl font-bold text-gray-800 mb-2">No se encontraron resultados</h3>
              <p className="text-gray-600">Intenta con otros términos de búsqueda o selecciona otra categoría</p>
            </div>
          )}
        </div>
      </section>

      {/* Modal */}
      {selectedPost && (
        <div 
          className="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 animate-fade-in"
          onClick={() => setSelectedPost(null)}
        >
          <div 
            className="bg-white rounded-3xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl animate-scale-in"
            onClick={(e) => e.stopPropagation()}
          >
            {/* Modal Header Image */}
            <div className="relative h-96 overflow-hidden">
              <img
                src={selectedPost.image}
                alt={selectedPost.title}
                className="w-full h-full object-cover"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
              
              {/* Close Button */}
              <button
                onClick={() => setSelectedPost(null)}
                className="absolute top-6 right-6 bg-white/90 backdrop-blur-sm rounded-full p-3 hover:bg-white transition-all shadow-lg group"
              >
                <X className="w-6 h-6 text-gray-800 group-hover:rotate-90 transition-transform duration-300" />
              </button>

              {/* Category & Date on Image */}
              <div className="absolute bottom-6 left-6 right-6">
                <div className="flex items-center gap-3 mb-3">
                  {(() => {
                    const cat = categories.find(c => c.name === selectedPost.category);
                    return (
                      <div className={`flex items-center gap-2 px-4 py-2 ${cat?.color || 'bg-gray-600'} text-white text-sm font-bold rounded-full shadow-lg`}>
                        <CategoryIcon category={selectedPost.category} />
                        <span>{selectedPost.category}</span>
                      </div>
                    );
                  })()}
                  <div className="flex items-center gap-2 px-4 py-2 bg-white/90 backdrop-blur-sm text-gray-800 text-sm font-semibold rounded-full">
                    <Calendar className="w-4 h-4" />
                    <span>{selectedPost.date}</span>
                  </div>
                </div>
                <h2 className="text-4xl font-extrabold text-white drop-shadow-lg">
                  {selectedPost.title}
                </h2>
              </div>
            </div>

            {/* Modal Content */}
            <div className="p-10">
              <div className="flex items-center justify-between mb-6 pb-6 border-b border-gray-200">
                <div className="flex items-center gap-3">
                  <div className="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                    {selectedPost.author.charAt(0)}
                  </div>
                  <div>
                    <p className="font-semibold text-gray-900">{selectedPost.author}</p>
                    <p className="text-sm text-gray-500">Publicado el {selectedPost.date}</p>
                  </div>
                </div>
              </div>

              <div className="prose prose-lg max-w-none mb-8">
                <p className="text-gray-700 text-lg leading-relaxed whitespace-pre-line">
                  {selectedPost.fullContent}
                </p>
              </div>

              {/* Tags */}
              <div className="mb-8">
                <h4 className="font-semibold text-gray-900 mb-3">Etiquetas:</h4>
                <div className="flex flex-wrap gap-2">
                  {selectedPost.tags.map((tag, idx) => (
                    <span key={idx} className="px-4 py-2 bg-gradient-to-r from-blue-50 to-purple-50 text-blue-700 text-sm font-medium rounded-full border border-blue-200">
                      #{tag}
                    </span>
                  ))}
                </div>
              </div>

              {/* Action Buttons */}
              <div className="flex gap-4">
                <button
                  onClick={() => setSelectedPost(null)}
                  className="flex-1 px-6 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105"
                >
                  Cerrar
                </button>
                <button className="px-6 py-4 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all">
                  Compartir
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

      <style jsx>{`
        @keyframes fade-in {
          from { opacity: 0; }
          to { opacity: 1; }
        }
        @keyframes scale-in {
          from { transform: scale(0.9); opacity: 0; }
          to { transform: scale(1); opacity: 1; }
        }
        .animate-fade-in {
          animation: fade-in 0.2s ease-out;
        }
        .animate-scale-in {
          animation: scale-in 0.3s ease-out;
        }
      `}</style>
    </>
  );
}