<footer class="bg-gradient-to-r from-brandBlack via-jet to-stone text-almondDark py-6 mt-auto">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Información -->
            <div>
                <h4 class="font-semibold text-khaki mb-2">Sistema de RRHH</h4>
                <p class="text-xs text-almond/70">
                    {{ \App\Support\Empresa::nombre() }}<br>
                    {{ \App\Support\Empresa::direccion() }}
                    @if(\App\Support\Empresa::ruc() !== '')
                        <br>RUC: {{ \App\Support\Empresa::ruc() }}
                    @endif
                </p>
            </div>

            <!-- Enlaces -->
            <div>
                <h4 class="font-semibold text-khaki mb-2">Enlaces</h4>
                <ul class="text-xs space-y-1">
                    <li><a href="https://www.transparencia.gob.pe/enlaces/pte_transparencia_enlaces.aspx?id_entidad=11098" class="hover:text-khakiLight">Portal Transparencia</a></li>
                    <li><a href="https://facilita.gob.pe/t/3423" class="hover:text-khakiLight">Mesa de Partes</a></li>
                    <li><a href="https://www.gob.pe/23060-presentar-un-reclamo-ante-una-entidad-publica?child=14972" class="hover:text-khakiLight">Libro de reclamaciones</a></li>
                </ul>
            </div>

            <!-- Contacto -->
            <div>
                <h4 class="font-semibold text-khaki mb-2">Contacto</h4>
                <ul class="text-xs text-almond/70 space-y-1">
                    <li><i class="fas fa-phone mr-2"></i>{{ \App\Support\Empresa::telefono() }}</li>
                    <li><i class="fas fa-envelope mr-2"></i>{{ \App\Support\Empresa::email() }}</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 mt-4 pt-4 text-center text-xs text-almond/50">
            &copy; {{ date('Y') }} {{ \App\Support\Empresa::nombre() }}. Todos los derechos reservados.
        </div>
    </div>
</footer>
