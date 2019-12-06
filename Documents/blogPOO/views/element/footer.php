            </section>
        </main>
        <footer>
            <div>
                <?php if(defined('DEBUG_TIME')) : ?>
                    <p>page généré en <?= round(1000 * microtime(true) - DEBUG_TIME) ?> millisecondes</p>
                <?php endif ?>
            </div>
            <div>
                <p>Réaliser par @Jonathan</p>
            </div>
        </footer>
        <script type="text/javascript" src="/js/main.js"></script>
        <script type="text/javascript" src="/js/triTable.js"></script>
    </body>
</html>